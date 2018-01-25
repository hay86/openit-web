<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use App\Address;
use Auth;
use Session;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        return view('account.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|string|max:191',
            'address_id'=> 'required|integer',
        ]);

        $user = Auth::user();
        $user->name = $request->name;

        $address = Address::find($request->address_id);
        if (!empty($address) && $address->user_id == $user->id) {
            $user->address_id = $address->id;
        }

        $user->save();

        Session::flash('status' , '用户信息修改成功！');

        return redirect()->route('account.edit');
    }

    public function referer() {
        $user = Auth::user();
        $coupons = [];
        $qrcodes = [];

        foreach ($user->coupons as $coupon) {
            if (strtotime($coupon->expired_at) >= time()) {
                $qrCode = new QrCode(route('order.create') . '?ref=' . $user->id . '&cpn=' . $coupon->id);
                $qrCode
                    ->setSize(200)
                    ->setMargin(5)
                    ->setWriterByName('png')
                    ->setErrorCorrectionLevel('high')
                    ->setForegroundColor(['r' => 51, 'g' => 51, 'b' => 51])
                    ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])
                    ->setLogoPath(resource_path('assets/img/rmb.png'))
                    ->setLogoWidth(66);
                $qrcodes[] = 'data:png;base64,' . base64_encode($qrCode->writeString());
                $coupons[] = $coupon;
            }
        }

        $qrCode = new QrCode(route('order.create') . '?ref=' . $user->id);
        $qrCode
            ->setSize(200)
            ->setMargin(5)
            ->setWriterByName('png')
            ->setErrorCorrectionLevel('high')
            ->setForegroundColor(['r' => 51, 'g' => 51, 'b' => 51])
            ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])
            ->setLogoPath(resource_path('assets/img/rmb.png'))
            ->setLogoWidth(66);
        $qrcodes[] = 'data:png;base64,' . base64_encode($qrCode->writeString());
        $coupons[] = null;

        return view('account.referer', ['qrcodes' => $qrcodes, 'coupons'=> $coupons]);
    }

    public function coupons() {
        $user = Auth::user();
        $coupons = [];

        foreach ($user->coupons as $coupon) {
            if (strtotime($coupon->expired_at) >= time()) {
                $coupons[] = $coupon;
            }
        }

        return view('account.coupons', ['coupons'=> $coupons]);
    }
}
