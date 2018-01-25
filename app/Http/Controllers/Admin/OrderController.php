<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use App\Http\Controllers\Controller;
use App\Order;
use App\Express;
use App\Box;
use App\SMS;
use Session;
use WeChat;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'user_id'       => 'nullable|integer',
            'referer_id'    => 'nullable|integer',
            'prefer_day'    => 'nullable|integer|between:1,7',
            'status'        => 'nullable|integer|min:0',
        ]);

        $orders = Order::with(['user', 'express']);

        if (isset($request->user_id))
            $orders = $orders->where('user_id', $request->user_id);
        if (isset($request->referer_id))
            $orders = $orders->where('referer_id', $request->referer_id);
        if (isset($request->prefer_day))
            $orders = $orders->where('prefer_day', $request->prefer_day);
        if (isset($request->status))
            $orders = $orders->where('status', $request->status);

        $orders = $orders->orderBy('id', 'desc')->paginate(20);

        return view('admin.orders.index', [
            'orders'        => $orders,
            'request'       => $request,
            'prefer_days'   => range(1,7),
            'statuses'      => array_keys(order_status('*')),
            'courier_firms' => array_keys(courier_firm('*')),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);

        return view('admin.orders.show', ['order' => $order]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'status'        => 'required|integer|min:0',
            'courier_num'   => 'nullable|string',
            'courier_firm'  => 'nullable|integer|min:1',
            'box_id'        => 'nullable|integer',
        ]);

        $order = Order::find($id);

        if ($order->status == Order::SUBMITTED && $request->status == Order::CANCELED) {
            $order->status = $request->status;
            $order->save();

            Session::flash('status', '订单 #' . $order->id . ' 取消成功！');
        } else if ($order->status == Order::SUBMITTED && $request->status == Order::CONFIRMED) {
            $order->status = $request->status;
            $order->save();

            Session::flash('status', '订单 #' . $order->id . ' 确认成功！');
        } else if ($order->status == Order::CONFIRMED && $request->status == Order::CANCELED) {
            $order->status = $request->status;
            $order->save();

            Session::flash('status', '订单 #' . $order->id . ' 取消成功！');
        } else if ($order->status == Order::SERVING && $request->status == Order::REFUND) {
            $result = WeChat::payment()->refund($order->id, $order->id, $order->cash_fee * 100, $order->refund * 100);

            if ($result->return_code === 'SUCCESS' && $result->result_code === 'SUCCESS') {
                $order->status = $request->status;
                $order->save();

                Session::flash('status', '订单 #' . $order->id . ' 申请退款成功！0-3个工作日到账！');
            }
            else {
                $err_msg = $result->err_code ? 'code=' . $result->err_code . ', des=' . $result->err_code_des : $result->return_msg;
                Session::flash('error', '订单 #' . $order->id . ' 申请退款失败！原因：' . $err_msg);
            }
        } else if ($order->status == Order::PACKING && $request->status == Order::PICK_UP) {
            if (isset($request->courier_num) && isset($request->courier_firm) && isset($request->box_id)) {
                // update box stock
                $box = Box::find($request->box_id);
                foreach ($box->products as $product) {
                    $product->decrementStockByUnit();
                }

                // update box express
                Box::where('id', $request->box_id)->increment('express', 1);

                // save express
                $express = new Express;
                $express->courier_num = $request->courier_num;
                $express->courier_firm = $request->courier_firm;
                $express->prefer_day = $order->prefer_day;
                $express->status = $request->status;
                $express->order_id = $order->id;
                $express->address_id = $order->address_id;
                $express->box_id = $request->box_id;
                $express->save();

                // update order status
                $order->status = $request->status;
                $order->express_id = $express->id;
                $order->save();

                Session::flash('status', '订单 #' . $order->id . ' 出库成功！');
            }
        } else if ($order->status == Order::PICK_UP && $request->status == Order::PICK_UP) {
            if (isset($request->courier_num) && isset($request->courier_firm) && isset($request->box_id)) {
                if ($order->express->box_id != $request->box_id) {
                    // update box stock
                    $box = Box::find($order->express->box_id);
                    foreach ($box->products as $product) {
                        $product->incrementStockByUnit();
                    }
                    $box = Box::find($request->box_id);
                    foreach ($box->products as $product) {
                        $product->decrementStockByUnit();
                    }

                    // update box express
                    Box::where('id', $order->express->box_id)->decrement('express', 1);
                    Box::where('id', $request->box_id)->increment('express', 1);
                }

                // update express
                $order->express->courier_num = $request->courier_num;
                $order->express->courier_firm = $request->courier_firm;
                $order->express->box_id = $request->box_id;
                $order->express->save();

                Session::flash('status', '订单 #' . $order->id . ' 出库修改成功！');
            }
        } else if ($order->status == Order::PICK_UP && $request->status == Order::DELIVERED) {
            if (isset($order->express_id)) {
                // update express status
                if ($order->express->status == Order::PICK_UP) {
                    $order->express->status = $request->status;
                    $order->express->save();
                }

                // update order status
                $order->status = $request->status;
                $order->balance -= 1;
                $order->save();

                $code = courier_firm($order->express->courier_firm)['code'];
                if ($code !== 'ZT') {
                    $time = expected_express_date($order->express->address->city);
                    SMS::deliveryNotificationByAliyun($order->express->address->mobile, $order->express->address->username, $time);
                }

                Session::flash('status', '订单 #' . $order->id . ' 发货成功！');
            }
        }

        return back()->withInput();
    }

    public function update_notes(Request $request, $id)
    {
        $this->validate($request, [
            'notes'         => 'nullable|string',
        ]);

        $order = Order::find($id);
        $order->notes = $request->notes;
        $order->save();

        return back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function print_sheet(Request $request, $id) {
        $order = Order::find($id);
        $qrcodes = [];

        if ($request->box_id) {
            $box = Box::find($request->box_id);
        }
        else {
            $box = $order->express->box;
        }

        foreach ($box->products as $product) {
            $qrCode = new QrCode(route('review.show', $product->id));
            $qrCode
                ->setSize(100)
                ->setMargin(0)
                ->setWriterByName('png')
                ->setErrorCorrectionLevel('high')
                ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0])
                ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])
                ->setLogoPath(resource_path('assets/img/rmb.png'))
                ->setLogoWidth(33);
            $qrcodes[] = 'data:png;base64,' . base64_encode($qrCode->writeString());
        }

        return view('admin.orders.print', ['order' => $order, 'box' => $box, 'qrcodes' => $qrcodes]);
    }

    public function print_coupon($id) {
        $order = Order::find($id);

        $qrCode = new QrCode(route('review.show', $id));
        $qrCode
            ->setSize(120)
            ->setMargin(0)
            ->setWriterByName('png')
            ->setErrorCorrectionLevel('high')
            ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0])
            ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])
            ->setLogoPath(resource_path('assets/img/rmb.png'))
            ->setLogoWidth(40);
        $qrcode = 'data:png;base64,' . base64_encode($qrCode->writeString());

        return view('admin.orders.coupon', ['order' => $order, 'qrcode' => $qrcode]);
    }

    public function notification(Request $request, $id) {
        $this->validate($request, [
            'status' => 'string',
        ]);

        $order = Order::find($id);
        $code = courier_firm($order->express->courier_firm)['code'];

        if ($code !== 'ZT') {
            if ($request->status == Express::DELIVERED) {
                $time = expected_express_date($order->express->address->city);
                SMS::deliveryNotificationByAliyun($order->express->address->mobile, $order->express->address->username, $time);
            }
            else if ($request->status == Express::DELAYED) {
                SMS::delayNotificationByAliyun($order->express->address->mobile, $order->express->address->username, '1天');
            }
        }

        Session::flash('status', '通知发送成功！');

        return back()->withInput();
    }
}
