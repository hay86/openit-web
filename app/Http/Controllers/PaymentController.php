<?php
/**
 * Created by PhpStorm.
 * User: xukf
 * Date: 25/04/2017
 * Time: 2:32 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use App\Order;
use App\Notice;
use Auth;
use Redis;
use WeChat;
use Log;

class PaymentController extends Controller
{
    protected $expireSeconds = 300;

    public function notifyWechat() {
        $response = WeChat::payment()->handleNotify(function($notify, $successful){
            $order = Order::find($notify->out_trade_no);

            if (!$order) {
                Log::error('Order not exist: ' . $notify->out_trade_no);
                return true;
            }

            if ($order->trade_time) {
                Log::error('Order already paid: ' . $notify->out_trade_no);
                return true;
            }

            if ($successful) {
                $order->trade_type  = $notify->trade_type;
                $order->trade_mch   = $notify->mch_id;
                $order->trade_bank  = $notify->bank_type;
                $order->trade_time  = $notify->time_end;
                $order->balance     = $order->product->times;
                $order->status      = Order::PAID;

                $order->save();

                Redis::set(redis_order_key($order->id), $notify->is_subscribe);
                Redis::expire(redis_order_key($order->id), $this->expireSeconds);

                Notice::payment($order);
            }
            else {
                $err_msg = 'code=' . $notify->err_code . ', des=' . $notify->err_code_des;
                Log::error('Payment fail: ' . $err_msg);

                Redis::set(redis_order_key($order->id), $err_msg);
                Redis::expire(redis_order_key($order->id), $this->expireSeconds);
            }

            return true;
        });

        return $response;
    }

    public function prepareWechat(Request $request, $order) {
        $order = Order::find($order);
        $user = Auth::user();

        if (!$order || $order->user_id != $user->id) {
            return response()->json([
                'status' => 'failed',
                'reason' => '订单不存在',
            ]);
        }

        // if not in wechat browser
        if (strpos($request->header('user_agent'), 'MicroMessenger') === false) {
            $attributes = [
                'trade_type'    => 'NATIVE',
                'body'          => $order->product->displayName,
                'detail'        => $order->product->displayName,
                'out_trade_no'  => $order->id,
                'total_fee'     => $order->cash_fee * 100,
                'product_id'    => $order->product->id,
            ];
        }
        else {
            $attributes = [
                'trade_type'    => 'JSAPI',
                'body'          => $order->product->displayName,
                'detail'        => $order->product->displayName,
                'out_trade_no'  => $order->id,
                'total_fee'     => $order->cash_fee * 100,
                'openid'        => $user->email,
            ];
        }

        $payment = WeChat::payment();
        $wechatOrder = new \EasyWeChat\Payment\Order($attributes);
        $result = $payment->prepare($wechatOrder);

        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
            if ($result->trade_type == 'NATIVE') {
                return response()->json([
                    'status'    => 'success',
                    'code_url'  => $result->code_url,
                ]);
            }
            else {
                return response()->json([
                    'status'    => 'success',
                    'config'    => $payment->configForJSSDKPayment($result->prepay_id),
                ]);
            }
        }
        else {
            $err_msg = $result->return_code == 'FAIL' ?
                $result->return_msg : 'code=' . $result->err_code . ', des=' . $result->err_code_des;
            Log::error('Payment fail: ' . $err_msg);

            return response()->json([
                'status' => 'failed',
                'reason' => '支付失败：' . $err_msg,
            ]);
        }
    }

    public function checkWechat($order) {
        $ret = ['status' => 'success'];
        $value = Redis::get(redis_order_key($order));

        if ($value) {
            if ($value == 'Y') {
                $ret['paid'] = true;
                $ret['subscribe'] = true;
            }
            else if ($value == 'N') {
                $ret['paid'] = true;
                $ret['subscribe'] = false;
            }
            else {
                $ret['paid'] = false;
                $ret['reason'] = $value;
            }
            Redis::del(redis_order_key($order));
        }

        return response()->json($ret);
    }

    public function showWechat(Request $request, $order) {
        $order = Order::find($order);

        if (!$order || $order->user_id != Auth::user()->id) {
            $order = null;
        }

        if (isset($request->code_url)) {
            $qrCode = new QrCode($request->code_url);
            $qrCode
                ->setSize(200)
                ->setMargin(5)
                ->setWriterByName('png')
                ->setErrorCorrectionLevel('high')
                ->setForegroundColor(['r' => 51, 'g' => 51, 'b' => 51])
                ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])
                ->setLogoPath(resource_path('assets/img/wx.png'))
                ->setLogoWidth(66);
            $qrCode = 'data:png;base64,' . base64_encode($qrCode->writeString());
        }
        else {
            $qrCode = null;
        }

        return view('payment.wechat', ['order' => $order, 'qrcode' => $qrCode, 'expire' => $this->expireSeconds]);
    }
}