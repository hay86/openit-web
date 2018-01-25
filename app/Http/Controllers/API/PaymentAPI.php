<?php
/**
 * Created by PhpStorm.
 * User: xukf
 * Date: 26/04/2017
 * Time: 6:16 PM
 */

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Order;
use WeChat;

class PaymentAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function query($order)
    {
        $order = Order::find($order);

        if (!$order) {
            return response()->json([
                'status' => 'failed',
                'reason' => '订单不存在',
            ]);
        }

        $result = WeChat::payment()->query($order->id);

        if (!$order->trade_time) {
            if ($result && $result->trade_state === 'SUCCESS') {    // paid
                $order->trade_type  = $result->trade_type;
                $order->trade_mch   = $result->mch_id;
                $order->trade_bank  = $result->bank_type;
                $order->trade_time  = $result->time_end;
                $order->balance     = $order->product->times;
                $order->status      = Order::PAID;

                $order->save();
            }
            else if (time() - $order->created_at->timestamp > 72*3600) {  // not paid over 72 hours
                if ($order->status == Order::CONFIRMED) {
                    $order->status = Order::CANCELED;

                    $order->save();
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'result' => $result,
        ]);
    }

    public function queryRefund($order)
    {
        $order = Order::find($order);

        if (!$order) {
            return response()->json([
                'status' => 'failed',
                'reason' => '订单不存在',
            ]);
        }

        $result = WeChat::payment()->queryRefund($order->id);

        if ($order->status == Order::REFUND) {
            if ($result && $result->refund_status_0 === 'SUCCESS') {
                $order->balance     = 0;
                $order->status      = Order::REFUNDED;

                $order->save();
            }
        }

        return response()->json([
            'status' => 'success',
            'result' => $result,
        ]);
    }
}