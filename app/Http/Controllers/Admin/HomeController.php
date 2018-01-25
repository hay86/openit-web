<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $buttons = [
            ['url' => route('admin.articles.index'),    'name' => '文章'],
            ['url' => route('admin.tags.index'),        'name' => '标签'],
            ['url' => route('admin.products.index'),    'name' => '商品'],
            ['url' => route('admin.orders.index'),      'name' => '订单'],
            ['url' => route('admin.statuses.index'),    'name' => '统计'],
            ['url' => route('admin.boxes.index'),       'name' => '库存'],
            ['url' => route('admin.coupons.index'),     'name' => '优惠券'],
            ['url' => route('admin.wechat.dyh.edit'),   'name' => '订阅号'],
            ['url' => route('admin.wechat.fwh.edit'),   'name' => '服务号'],
        ];

        return view('admin.home', ['buttons' => $buttons]);
    }
}
