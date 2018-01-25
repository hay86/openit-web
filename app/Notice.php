<?php
/**
 * Created by PhpStorm.
 * User: xukf
 * Date: 04/05/2017
 * Time: 8:16 PM
 */

namespace App;

use WeChat;

class Notice
{
    public static function payment($order) {
        $notice = WeChat::notice();
        $users = notice_users();

        foreach ($users as $user)
        $notice->send([
            'touser'        => $user,
            'template_id'   => 'wN36mdMgsTJB4mLW2ruuKndEkuoWKBCx2V0Z_9y8Spk',
            'url'           => route('admin.orders.show', $order->id),
            'data'          => [
                'first'             => sprintf('「%s」付款成功！', $order->user->name),
                'orderMoneySum'     => '¥' . $order->cash_fee,
                'orderProductName'  => $order->product->displayName,
                'Remark'            => sprintf("首次配送：%s %s\n收货地址：%s\n%s",
                                        date('n月j日', $order->next_express),
                                        week_day($order->prefer_day),
                                        $order->address->contact_string,
                                        $order->address->address_string),
            ],
        ]);
    }

    public static function kf_message($message) {
        $notice = WeChat::notice();
        $users = notice_users();

        foreach ($users as $user)
            $notice->send([
                'touser'        => $user,
                'template_id'   => '5OFJOLi3_nKzAo4RG8Y6TYPmtOXYUIAXCNViHszewVg',
                'url'           => 'http://mpkf.weixin.qq.com',
                'data'          => [
                    'first'     => $message,
                    'keyword1'  => date('H:i:s'),
                    'keyword2'  => 1,
                    'remark'    => '',
                ],
            ]);
    }
}