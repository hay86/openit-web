<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Endroid\QrCode\QrCode;
use Redis;
use Auth;
use Session;
use Redirect;
use WeChat;

class LoginWechatController extends Controller
{
    protected $redirectTo = '/';
    protected $tokenName = 'token.wechat';
    protected $expireSeconds = 300;

    public function showLoginQRCode()
    {
        $token = rand_token(6);

        for ($i=0; $i<3; $i++) {
            if (!Redis::exists(redis_token_key($token))) break;
            $token = rand_token(6);
        }

        Session::put($this->tokenName, $token);
        Redis::set(redis_token_key($token), null);
        Redis::expire(redis_token_key($token), $this->expireSeconds);

        $qrCode = new QrCode(route('login.wechat.auth', $token));
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

        return view('auth.login_wechat', ['qrcode' => $qrCode, 'expire' => $this->expireSeconds]);
    }

    public function openQRCode($id)
    {
        $success = false;

        if (Redis::exists(redis_token_key($id))) {
            Redis::set(redis_token_key($id), Auth::user()->id);
            Redis::expire(redis_token_key($id), $this->expireSeconds);
            $success = true;
        }

        return view('auth.login_wechat_auth', ['success' => $success, 'js' => WeChat::js()]);
    }

    public function authCheck()
    {
        $token = Session::get($this->tokenName);
        if (empty($token)) {
            return response()->json(['status' => 'success', 'authorized'=> false]);
        }

        $user_id = Redis::get(redis_token_key($token));
        if (empty($user_id)) {
            return response()->json(['status' => 'success', 'authorized'=> false]);
        }

        Session::forget($this->tokenName);
        Redis::del(redis_token_key($token));
        Auth::loginUsingId($user_id);

        $intended_url = Redirect::intended()->getTargetUrl();

        return response()->json(['status' => 'success', 'authorized'=> true, 'intended_url' => $intended_url]);
    }
}
