<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Address;
use App\Coupon;
use App\Product;
use App\Order;
use App\Express;
use App\Tracking;
use Auth;
use Session;
use Validator;
use WeChat;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Auth::user()->orders()->paginate(10);

        return view('order.index', ['orders' => $orders]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $coupon = null;
        $coupons = [];

        if ($request->cpn && $request->ref) {
            $coupon = Coupon::find($request->cpn);
            if (!empty($coupon) && $coupon->user_id != $request->ref) {
                $coupon = null;
            }
        }

        if ($coupon === null) {
            foreach ($user->coupons as $coupon) {
                if (strtotime($coupon->expired_at) >= time()) {
                    $coupons[] = $coupon;
                }
            }
            $coupons[] = null;
            if (count($coupons) > 0) {
                $coupon = $coupons[0];
            }
        }

        if ($user->isAdmin()) {
            if ($request->item && is_numeric($request->item)) {
                $products = Product::withTrashed()->where('id', $request->item)->get();
            }
            else {
                $products = Product::withTrashed()->where('type', Product::PACK)->orderBy('times', 'asc')->get();
            }
            $prefer_days = range(1,7);
        }
        else {
            if ($request->item && is_numeric($request->item)) {
                $products = Product::where('id', $request->item)->get();
            }
            else {
                $products = Product::where('type', Product::PACK)->orderBy('times', 'asc')->get();
            }
            $prefer_days = [1,3,6];
        }

        if (count($products) > 0 && $products[0]->type === Product::PACK) {
            $tastes = ['无偏好' => '', '甜味' => '甜味', '咸味' => '咸味', '女生' => '女生', '儿童' => '儿童'];
        }
        else {
            $tastes = [];
        }

        if ($request->cpn && $coupon === null) {
            Session::flash('error' , '优惠券已被使用！');
        }

        return view('order.create', [
            'tastes'        => $tastes,
            'products'      => $products,
            'prefer_days'   => $prefer_days,
            'user'          => $user,
            'referer_id'    => $request->ref,
            'coupon'        => $coupon,
            'coupons'       => $coupons
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // close transaction
        Session::flash('error', '对不起，系统升级中，暂时无法下单！');
        return redirect()->back()->withInput();

        $validator = Validator::make($request->all(), [
            'product_id'    => 'required|integer',
            'prefer_day'    => 'required|integer|between:1,7',
            'address_id'    => 'required|integer',
            'coupon_id'     => 'nullable|string',
            'referer_id'    => 'nullable|integer',
            'invoice'       => 'nullable|string',
            'notes'         => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        // check product
        if ($user->isAdmin()) {
            $product = Product::withTrashed()->find($request->product_id);
        }
        else {
            $product = Product::find($request->product_id);
        }
        if (empty($product)) {
            $validator->getMessageBag()->add('product_id', '商品 不存在。');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // check address, calculate express fee
        $express_fee = 0;
        $address = Address::find($request->address_id);
        if (empty($address) || $address->user_id != $user->id) {
            $validator->getMessageBag()->add('address_id', '地址 不存在。');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // check referer
        if (!empty($request->referer_id)) {
            if ($request->referer_id == $user->id) {
                $request->referer_id = null;
            }
        }

        // check coupon, calculate coupon fee
        $coupon_fee = 0;
        if (!empty($request->coupon_id)) {
            $coupon = Coupon::find($request->coupon_id);
            if (!empty($coupon) && strtotime($coupon->expired_at) >= time() &&
                in_array($coupon->user_id, [$user->id, $request->referer_id])) {
                $coupon_fee = $coupon->discount;
            } else {
                $request->coupon_id = null;
                Session::flash('error' , '优惠券已被使用！');
            }
        }

        $id = gen_order_id($user->id);

        $order              = new Order;
        $order->id          = $id;
        $order->express_fee = $express_fee;
        $order->coupon_fee  = $coupon_fee;
        $order->total_fee   = $product->price + $order->express_fee;
        $order->cash_fee    = max($order->total_fee - $order->coupon_fee, 0);
        $order->prefer_day  = $request->prefer_day;
        $order->balance     = 0;
        $order->status      = Order::CONFIRMED;
        $order->user_id     = $user->id;
        $order->product_id  = $request->product_id;
        $order->address_id  = $request->address_id;
        $order->invoice     = $request->invoice;
        $order->coupon_id   = $request->coupon_id;
        $order->referer_id  = $request->referer_id;
        $order->notes       = $request->notes;

        if (!empty($order->coupon_id)) {
            Coupon::find($order->coupon_id)->delete();
        }

        $order->save();

        Session::flash('status' , '订单 #' . $id . ' 创建成功！');

        return redirect()->route('order.show', $id);
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

        if (empty($order) || $order->user_id != Auth::user()->id) {
            $order = null;
        }

        return view('order.show', ['order' => $order, 'js' => WeChat::js()]);
    }

    public function showExpress($id)
    {
        $expresses = [];
        $order = Order::find($id);

        if ($order && ($order->user_id == Auth::user()->id || Auth::user()->isAdmin())) {
            if ($order->status == Order::DELIVERED) {
                $code = courier_firm($order->express->courier_firm)['code'];
                $num = $order->express->courier_num;

                if ($code === 'ZT') {
                    $order->express->status = Order::RECEIVED;
                    $order->express->save();
                    $order->status = Order::RECEIVED;
                    $order->save();
                }
                else {
                    $track = new Tracking;
                    $track_info = $track->getTrackInfoByKDN($code, $num);

                    if ($track_info && $track_info->Success) {
                        if ($track_info->State == 3) {
                            $order->express->status = Order::RECEIVED;
                            $order->status = Order::RECEIVED;
                            $order->save();
                        }

                        $order->express->track_info = $track_info;
                        $order->express->save();
                    }
                }
            }

            $expresses = Express::with('address')->where('order_id', $id)->orderBy('id', 'desc')->get();
        }

        return view('order.express', ['expresses' => $expresses]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);
        $user = Auth::user();

        if (empty($order) || $order->user_id != $user->id) {
            $order = null;
        }

        if ($user->isAdmin()) {
            $prefer_days = range(1,7);
            $delay_weeks = range(1,4);
        }
        else {
            $prefer_days = [1,3,6];
            $delay_weeks = [1,2];
        }

        return view('order.edit', ['order' => $order, 'prefer_days' => $prefer_days, 'delay_weeks' => $delay_weeks, 'user' => $user]);
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
        $validator = Validator::make($request->all(), [
            'prefer_day'    => 'required|integer|between:1,7',
            'address_id'    => 'required|integer',
            'delay_week'    => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        // check address
        $address = Address::find($request->address_id);
        if (empty($address) || $address->user_id != $user->id) {
            $validator->getMessageBag()->add('address_id', '地址 不存在。');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // check order
        $order = Order::find($id);
        if (empty($order) || $order->user_id != $user->id) {
            $validator->getMessageBag()->add('id', '订单 不存在。');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $order->address_id = $request->address_id;
        $order->prefer_day = $request->prefer_day;

        if ($request->delay_week == 0) {
            if ($order->status == Order::DELAYED) {
                $order->status = Order::SERVING;
            }
        }
        else {
            if (in_array($order->status, [Order::PAID, Order::SERVING, Order::RECEIVED])) {
                $order->express_since = date('Y-m-d', $request->delay_week);
                $order->status = Order::DELAYED;
            }
        }

        $order->save();

        Session::flash('status' , '订单 #' . $id . ' 修改成功！');

        return redirect()->route('order.show', $id);
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
}
