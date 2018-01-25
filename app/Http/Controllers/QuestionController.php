<?php

namespace App\Http\Controllers;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = [
            ['title' => '修改收货时间地点', 'body' => '进入「<a href="' . route('order.index') . '">我的订单</a>」，找到需要修改的订单，点击「查看订单」，进入订单后，点击「修改收货时间地点」。'],
            ['title' => '可配送城市', 'body' => '目前仅支持上海同城配送。'],
            ['title' => '申请延期配送', 'body' => '进入「<a href="' . route('order.index') . '">我的订单</a>」，找到需要延期的订单，点击「查看订单」，进入订单后，点击「修改收货时间地点」，可选「延期1周」或「延期2周」。'],
            ['title' => '配送达到时间', 'body' => '我们会在收货日的前2天寄出包裹，一般情况下，您会在收货日当天收到，但不排除快递公司的原因导致的延误，建议您通过「<a href="' . route('order.index') . '">我的订单</a>」中的「查看物流」实时跟踪。'],
            ['title' => '会收到重复的零食吗', 'body' => '同一个订单不会收到重复的零食。老订单完成后新下的订单也不会收到重复的零食。新老订单如果在同一周配送，则肯定会收到重复的零食。'],
            ['title' => '能否排除某类零食', 'body' => '可以，请联系人工客服帮您备注，替换的零食会从往期中随机选择。'],
            ['title' => '能否单独购买', 'body' => '可以，请联系人工客服帮您下单，目前仅支持往期整盒购买，不支持单件零食购买。'],
            ['title' => '给亲友预订', 'body' => '下单时只需填写亲友的收货地址和联系方式即可。'],
            ['title' => '邀请朋友预订', 'body' => '进入「<a href="' . route('account.referer') . '">邀请朋友</a>」，长按二维码，点击「发送给朋友」，让对方也长按二维码，点击「识别图中二维码」，即可下单。'],
            ['title' => '取消订单申请退款', 'body' => '已支付的订单支持取消退款，具体请联系人工客服。'],
            ['title' => '人工客服', 'body' => '关注「Openit」官方微信，直接留言即可，我们会有客服人员与您取得联系，客服时间为周一至周五，9:00-21:00。<h1 class="text-center"><img src="http://static.openit.shop/qrcode_258.jpg" alt="微信搜索公众号「Openit」" width="150"></h1><h4 class="text-center"><span class="label label-success">使用微信扫码关注</span></h4>'],
        ];

        return view('question', ['questions' => $questions]);
    }
}
