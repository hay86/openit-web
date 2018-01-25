<?php

if (! function_exists('static_url')) {

    function static_url($filename)
    {
        return 'http://' . config('oss.static_domain') . '/' . $filename;
    }
}

if (! function_exists('holidays')) {
    function holidays($year)
    {
        switch ($year) {
            case 2017:
                return [
                    '0429','0430','0501','0502','0503','0504','0505','0506','0507', '0508',
                    '0509','0510','0511','0512','0513','0514','0515','0516','0517',
                    '0622','0623','0624','0625','0626','0627','0628',
                    '1001','1002', '1003','1004','1005','1006','1007','1008'
                ];
            case 2018:
                return [

                ];
            default:
                throw new Exception("未知年份");
        }
    }
}

if (! function_exists('express_date')) {

    function express_date($prefer_day, $base_ts = null, $delay_days = \App\Order::PACKING_IN_ADVANCE)
    {
        $oneDay = 24 * 3600;
        $oneWeek = 7 * $oneDay;

        $base_ts = ($base_ts === null) ? strtotime('Tomorrow') : strtotime(date('Y-m-d', $base_ts+$oneDay));
        $targetDay = $base_ts + $delay_days * $oneDay;

        switch ($prefer_day) {
            case 1:
                $timestamp = strtotime('Monday');
                break;
            case 2:
                $timestamp = strtotime('Tuesday');
                break;
            case 3:
                $timestamp = strtotime('Wednesday');
                break;
            case 4:
                $timestamp = strtotime('Thursday');
                break;
            case 5:
                $timestamp = strtotime('Friday');
                break;
            case 6:
                $timestamp = strtotime('Saturday');
                break;
            case 7:
                $timestamp = strtotime('Sunday');
                break;
            default:
                throw new Exception("未知日期");
        }
        while (true) {
            $receive_year = date('Y', $timestamp);
            $receive_date = date('md', $timestamp);

            if ($timestamp >= $targetDay && !in_array($receive_date, holidays($receive_year)))
                return $timestamp;
            $timestamp += $oneWeek;
        }
    }
}

if (! function_exists('week_day')) {
    function week_day($index)
    {
        if ($index < 1 || $index > 7)
            return '未知';
        $week_days = ['周一','周二','周三','周四','周五','周六','周日'];
        return $week_days[$index-1];
    }
}

if (! function_exists('duration')) {
    function duration($times)
    {
        if ($times < 4) {
            return $times . '周';
        }
        else {
            return round($times / 4) . '个月';
        }
    }
}

if (! function_exists('gen_order_id')) {
    function gen_order_id($user_id)
    {
        // Part 1: timestamp since 2014.1.1, 1388534400 = 20140101
        // Part 2: last 4 digits of user id
        // Part 3: random 0-9
        // Part 4: random 0-9
        return intval(sprintf('%d%04d%d%d', time()-1388534400, $user_id%10000, random_int(0,9), random_int(0,9)));
    }
}

if (! function_exists('order_status')) {
    function order_status($code)
    {
        $data = [
            \App\Order::CANCELED    => '订单取消',
            \App\Order::SUBMITTED   => '订单提交',
            \App\Order::CONFIRMED   => '订单确认',
            \App\Order::PAID        => '支付完成',
            \App\Order::SERVING     => '服务中',
            \App\Order::PACKING     => '等待出库',
            \App\Order::PICK_UP     => '等待发货',
            \App\Order::DELIVERED   => '已发货',
            \App\Order::RECEIVED    => '收货完成',
            \App\Order::FINISHED    => '订单完成',
            \App\Order::DELAYED     => '延期配送',
            \App\Order::REFUND      => '退款中',
            \App\Order::REFUNDED    => '退款完成',
        ];

        if ($code === '*')
            return $data;
        else if (isset($data[$code]))
            return $data[$code];
        else
            throw new Exception("未知状态");
    }
}

if (! function_exists('rand_token')) {
    function rand_token($digit)
    {
        $token = '';
        for ($i=0; $i<$digit; $i++) {
            $token .= base_convert(random_int(0, 35), 10, 36);
        }
        return $token;
    }
}

if (! function_exists('redis_token_key')) {
    function redis_token_key($token)
    {
        return 't:' . $token;
    }
}

if (! function_exists('redis_order_key')) {
    function redis_order_key($order)
    {
        return 'o:' . $order;
    }
}

if (! function_exists('price_to_value')) {
    function price_to_value($price)
    {
        return round($price * 100);
    }
}

if (! function_exists('value_to_price')) {
    function value_to_price($value)
    {
        if ($value % 100 == 0) {
            return sprintf('%.0f', $value / 100);
        }
        elseif ($value % 10 == 0) {
            return sprintf('%.1f', $value / 100);
        }
        else {
            return sprintf('%.2f', $value / 100);
        }
    }
}

if (! function_exists('product_type')) {
    function product_type($code)
    {
        $data = [
            \App\Product::RETAIL    => '零售',
            \App\Product::PACK      => '套餐',
        ];

        if ($code === '*')
            return $data;
        else if (isset($data[$code]))
            return $data[$code];
        else
            throw new Exception("未知类型");
    }
}

if (! function_exists('courier_firm')) {
    function courier_firm($code)
    {
        $data = \App\Tracking::KDN_COURIER_FIRMS;

        if ($code === '*')
            return $data;
        else if (isset($data[$code]))
            return $data[$code];
        else
            throw new Exception("未知快递");
    }
}

if (! function_exists('notice_users')) {
    function notice_users()
    {
        return [
            'oR7aRwGWryd5d_zg0E7l7VdN6VCQ',
        ];
    }
}

if (! function_exists('expected_express_date')) {
    function expected_express_date($city)
    {
        return in_array($city, ['上海市']) ? '明天' : '后天';
    }
}

if (! function_exists('sweetness')) {
    function sweetness($value)
    {
        if ($value >= 3)
            return '极甜';
        else if ($value == 2)
            return '甜';
        else if ($value == 1)
            return '微甜';
        else if ($value == 0)
            return '适中';
        else if ($value == -1)
            return '微咸';
        else if ($value == -2)
            return '咸';
        else
            return '极咸';
    }
}

if (! function_exists('hardness')) {
    function hardness($value)
    {
        if ($value >= 3)
            return '极硬';
        else if ($value == 2)
            return '硬';
        else if ($value == 1)
            return '较硬';
        else if ($value == 0)
            return '适中';
        else if ($value == -1)
            return '较软';
        else if ($value == -2)
            return '软';
        else
            return '极软';
    }
}

if (! function_exists('shelf_life')) {
    function shelf_life($value)
    {
        if ($value >= 90)
            return intval($value / 30) . '个月';
        else
            return $value . '天';
    }
}