<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Core\Exceptions\HttpException;
use EasyWeChat\Message\Transfer;
use App\Notice;
use Session;

class WechatController extends Controller
{
    private static $fwh = null;
    private static $dyh = null;
    private static $txh = null;

    public static function fwh() {
        if (self::$fwh === null) {
            $config = config('wechat');
            $config['app_id'] = env('WECHAT_APPID_FWH');
            $config['secret'] = env('WECHAT_SECRET_FWH');

            self::$fwh = new Application($config);
        }
        return self::$fwh;
    }

    public static function dyh() {
        if (self::$dyh == null) {
            $config = config('wechat');
            $config['app_id'] = env('WECHAT_APPID_DYH');
            $config['secret'] = env('WECHAT_SECRET_DYH');

            self::$dyh = new Application($config);
        }
        return self::$dyh;
    }

    public static function txh() {
        if (self::$txh == null) {
            $config = config('wechat');
            $config['app_id'] = env('WECHAT_APPID_TXH');
            $config['secret'] = env('WECHAT_SECRET_TXH');

            self::$txh = new Application($config);
        }
        return self::$txh;
    }

    public function serve_fwh() {
        $server = self::fwh()->server;
        $server->setMessageHandler([$this, 'openitHandler']);
        return $server->serve();
    }

    public function serve_dyh() {
        $server = self::dyh()->server;
        $server->setMessageHandler([$this, 'openitHandler']);
        return $server->serve();
    }

    public function serve_txh() {
        $server = self::txh()->server;
        $server->setMessageHandler([$this, 'tianxiaHandler']);
        return $server->serve();
    }

    public function openitHandler($message) {
        $empty = '';
        $transfer = new Transfer;
        $subscribe = "客官请留步！从今天起，就由 Openit 罩你了，上树掏的鸟蛋，有我一个，也有你一个；下河摸的丁丁鱼，有我一条，也有你一条。Openit 愿用无端宠溺，免你半世流离！\n\n点击 “<a href=\"http://www.openit.shop/order/create\">立即购买</a>” 带我走，让我成为你三生三世里最幸福的决定！";
        switch ($message->MsgType) {
            case 'text':
                Notice::kf_message($message->Content);
                return $transfer;
            case 'image':
                Notice::kf_message('发了一张图片');
                return $transfer;
            case 'voice':
                Notice::kf_message('发了一条语音');
                return $transfer;
            case 'video':
                Notice::kf_message('发了一个视频');
                return $transfer;
            case 'shortvideo':
                Notice::kf_message('发了一个小视频');
                return $transfer;
            case 'event':
                switch ($message->Event) {
                    case 'subscribe':
                        return $subscribe;
                    case 'unsubscribe':
                        return $empty;
                    case 'SCAN':
                        return $subscribe;
                    case 'LOCATION':
                        return $empty;
                    case 'CLICK':
                        return $empty;
                    case 'VIEW':
                        return $empty;
                    default:
                        return $empty;
                }
            case 'location':
                Notice::kf_message('发了地理位置');
                return $transfer;
            case 'link':
                Notice::kf_message('发了一个链接');
                return $transfer;
            default:
                return $empty;
        }
    }

    public function tianxiaHandler($message) {
        $empty = '';
        $subscribe = "天下零食，尽在掌握。每日为您推送最诱人的零食资讯！\n\n<a href=\"http://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MjM5NzQ4Mzc2MQ==&scene=124#wechat_redirect\">「点击查看历史消息」</a>";
        switch ($message->MsgType) {
            case 'text':
                return $subscribe;
            case 'image':
                return $empty;
            case 'voice':
                return $empty;
            case 'video':
                return $empty;
            case 'shortvideo':
                return $empty;
            case 'event':
                switch ($message->Event) {
                    case 'subscribe':
                        return $subscribe;
                    case 'unsubscribe':
                        return $empty;
                    case 'SCAN':
                        return $subscribe;
                    case 'LOCATION':
                        return $empty;
                    case 'CLICK':
                        return $empty;
                    case 'VIEW':
                        return $empty;
                    default:
                        return $empty;
                }
            case 'location':
                return $empty;
            case 'link':
                return $empty;
            default:
                return $empty;
        }
    }

    public function edit_fwh() {
        try {
            $menus = self::fwh()->menu->all();
            $menus = $menus['menu']['button'];
        } catch (HttpException $e) {
            Session::flash('status' , $e->getMessage());
            $menus = [];
        }

        return view('admin.wechat.fwh', ['menus' => json_encode($menus, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)]);
    }

    public function edit_dyh() {
        try {
            $menus = self::dyh()->menu->all();
            $menus = $menus['menu']['button'];
        } catch (HttpException $e) {
            Session::flash('status' , $e->getMessage());
            $menus = [];
        }

        return view('admin.wechat.dyh', ['menus' => json_encode($menus, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)]);
    }

    public function edit_txh() {
        try {
            $menus = self::txh()->menu->all();
            $menus = $menus['menu']['button'];
        } catch (HttpException $e) {
            Session::flash('status' , $e->getMessage());
            $menus = [];
        }

        return view('admin.wechat.txh', ['menus' => json_encode($menus, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)]);
    }

    public function update_fwh(Request $request) {
        $this->validate($request, [
            'menus'     => 'required|json',
        ]);

        $menu = self::fwh()->menu;
        $menu->destroy();
        $menu->add(json_decode($request->menus, true));

        Session::flash('status' , '微信 服务号 更新成功！');

        return redirect()->route('admin.wechat.fwh.edit');
    }

    public function update_dyh(Request $request) {
        $this->validate($request, [
            'menus'     => 'required|json',
        ]);

        $menu = self::dyh()->menu;
        $menu->destroy();
        $menu->add(json_decode($request->menus, true));

        Session::flash('status' , '微信 订阅号 更新成功！');

        return redirect()->route('admin.wechat.dyh.edit');
    }

    public function update_txh(Request $request) {
        $this->validate($request, [
            'menus'     => 'required|json',
        ]);

        $menu = self::txh()->menu;
        $menu->destroy();
        $menu->add(json_decode($request->menus, true));

        Session::flash('status' , '微信 天下号 更新成功！');

        return redirect()->route('admin.wechat.txh.edit');
    }
}
