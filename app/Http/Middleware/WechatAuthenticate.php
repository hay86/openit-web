<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;
use Redirect;

class WechatAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!empty(session('wechat.oauth_user'))) {
            // if wechat user authorized
            $wechat_user = session('wechat.oauth_user');
            $wechat_user_json = json_encode($wechat_user, JSON_UNESCAPED_UNICODE);

            if (Auth::guest() ||
                Auth::user()->email     !== $wechat_user->id ||
                Auth::user()->name      !== $wechat_user->name ||
                Auth::user()->avatar    !== $wechat_user->avatar ||
                Auth::user()->profile   !== $wechat_user_json) {

                // if openit user not login or wrong user
                $user = User::where('email', $wechat_user->id)->first();

                if (empty($user)) {
                    // if openit user not exists
                    $user = User::create([
                        'name'      => $wechat_user->name,
                        'avatar'    => $wechat_user->avatar,
                        'email'     => $wechat_user->id,
                        'password'  => bcrypt($wechat_user->id),
                        'profile'   => $wechat_user_json,
                    ]);
                }
                else {
                    $user->name = $wechat_user->name;
                    $user->avatar = $wechat_user->avatar;
                    $user->profile = $wechat_user_json;
                    $user->save();
                }

                Auth::loginUsingId($user->id);
            }
        }
        elseif (Auth::guest()) {
            return Redirect::guest('/login/wechat');
        }

        return $next($request);
    }
}
