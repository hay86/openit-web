<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Coupon;
use App\User;
use Session;

class CouponController extends Controller
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
    public function index()
    {
        $coupons = Coupon::withTrashed()->with('user')->paginate(20);

        return view('admin.coupons.index', ['coupons' => $coupons]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = empty($request->user_id) ? null : User::find($request->user_id);
        
        return view('admin.coupons.create', ['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'discount'      => 'required|integer|min:1|max:50',
            'expired_in'    => 'required|integer|between:1,12',
            'user_id'       => 'required|integer',
        ]);

        $id = rand_token(8);

        for ($i=0; $i<3; $i++) {
            if (empty(Coupon::find($id))) break;
            $id = rand_token(8);
        }

        $user = User::find($request->user_id);

        $coupon = new Coupon;
        $coupon->id = $id;
        $coupon->discount = $request->discount;
        $coupon->expired_at = date('Y-m-d', strtotime('+' . $request->expired_in . ' month'));
        $coupon->user_id = $user->id;
        $coupon->save();

        Session::flash('status' , '优惠券 <' . $id . '> 创建成功！');

        return redirect()->route('admin.coupons.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $coupons = Coupon::withTrashed()->with('user')->where('user_id', $id)->paginate(20);

        return view('admin.coupons.show', ['coupons' => $coupons, 'user' => $user]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $coupon = Coupon::find($id);
        $coupon->delete();

        Session::flash('status' , '优惠券 <' . $id . '> 删除成功！');

        return redirect()->route('admin.coupons.index');
    }
}
