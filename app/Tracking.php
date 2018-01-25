<?php
/**
 * Created by PhpStorm.
 * User: xukf
 * Date: 06/04/2017
 * Time: 10:48 AM
 */

namespace App;


class Tracking{

    const KDN_EBusinessID   = '1283498';
    const KDN_AppKey        = '935373d6-a861-4639-b6a4-ab092d40a4df';
    const KDN_ReqURL        = 'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx';
    const KDN_COURIER_FIRMS = [
        1   => [
            'name'      => '圆通速递',
            'code'      => 'YTO',
            'type'      => 'express',
            'phone'     => '95554',
            'homepage'  => 'http://www.yto.net.cn/',
            'picture'   => 'http://cdn.trackingmore.com/images/icons/express/yto.png',
        ],
        2   => [
            'name'      => '中通快递',
            'code'      => 'ZTO',
            'type'      => 'express',
            'phone'     => '95311',
            'homepage'  => 'http://www.zto.cn',
            'picture'   => 'http://cdn.trackingmore.com/images/icons/express/zto.png',
        ],
        3   => [
            'name'      => '申通快递',
            'code'      => 'STO',
            'type'      => 'express',
            'phone'     => '95543',
            'homepage'  => 'http://www.sto.cn',
            'picture'   => 'http://cdn.trackingmore.com/images/icons/express/sto.png',
        ],
        4   => [
            'name'      => '百世快递',
            'code'      => 'HTKY',
            'type'      => 'express',
            'phone'     => '95320',
            'homepage'  => 'http://www.800bestex.com',
            'picture'   => 'http://cdn.trackingmore.com/images/icons/express/bestex.png',
        ],
        5   => [
            'name'      => '韵达速递',
            'code'      => 'YD',
            'type'      => 'express',
            'phone'     => '95546',
            'homepage'  => 'http://www.yundaex.com',
            'picture'   => 'http://cdn.trackingmore.com/images/icons/express/yunda.png',
        ],
        6   => [
            'name'      => '邮政EMS',
            'code'      => 'EMS',
            'type'      => 'globalpost',
            'phone'     => '11183',
            'homepage'  => 'http://www.ems.com.cn',
            'picture'   => 'http://cdn.trackingmore.com/images/icons/express/companylogo/3011.jpg',
        ],
        7   => [
            'name'      => '顺丰速运',
            'code'      => 'SF',
            'type'      => 'express',
            'phone'     => '95338',
            'homepage'  => 'http://www.sf-express.com',
            'picture'   => 'http://cdn.trackingmore.com/images/icons/express/sf-express.png',
        ],
        8   => [
            'name'      => '自提',
            'code'      => 'ZT',
            'type'      => 'express',
            'phone'     => '99999',
            'homepage'  => 'http://www.openit.shop',
            'picture'   => 'http://static.openit.shop/favicon-96x96.png',
        ],
    ];

    /**
     * Json方式 查询订单物流轨迹
     */
    function getTrackInfoByKDN($shipper_code, $logistic_code) {
        if ($shipper_code === 'ZT') // 自提
            return null;
        else if ($shipper_code === self::KDN_COURIER_FIRMS[4]['code']) {
            $result = $this->getTrackInfoByBestEx($shipper_code, $logistic_code);
        }

        if ($result)
            return $result;

        $requestData= "{'ShipperCode':'" . $shipper_code . "','LogisticCode':'" . $logistic_code . "'}";

        $data = array(
            'EBusinessID' => self::KDN_EBusinessID,
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );

        $data['DataSign'] = urlencode(base64_encode(md5($requestData.self::KDN_AppKey)));

        // send post
        $temps = array();
        foreach ($data as $key => $value)
            $temps[] = sprintf('%s=%s', $key, $value);

        $post_data = implode('&', $temps);
        $url_info = parse_url(self::KDN_ReqURL);

        if (empty($url_info['port']))
            $url_info['port'] = 80;

        $header = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $header .= "Host:" . $url_info['host'] . "\r\n";
        $header .= "Content-Type:application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length:" . strlen($post_data) . "\r\n";
        $header .= "Connection:close\r\n\r\n";
        $header .= $post_data;

        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $header);

        $output = "";
        while (!feof($fd)) {
            if (($chr = @fgets($fd)) && ($chr == "\r\n" || $chr == "\n"))
                break;
        }
        while (!feof($fd)) {
            $output .= fread($fd, 128);
        }
        fclose($fd);

        return json_decode($output);
    }

    function getTrackInfoByBestEx($shipper_code, $logistic_code) {
        $error = null;
        $timeout = 10000;
        $headers = ['Content-Type:application/x-www-form-urlencoded'];
        $response = Proxy::curl('http://www.800bestex.com/Bill/Track', 'code='.$logistic_code, $timeout, $error, $headers);

        if (!$response)
            return false;

        $result = [
            'EBusinessID'   => '1283498',
            'ShipperCode'   => $shipper_code,
            'Success'       => true,
            'LogisticCode'  => $logistic_code,
            'State'         => '2',
            'Traces'        => [],

        ];

        $start = strpos($response, '<tr data-type=', 0);
        $end = $start ? strpos($response, '</tr>', $start) : false;

        while ($end) {
            $str = substr($response, $start, $end-$start);
            if (strpos($str, '签收') !== false)
                $result['State'] = '3';
            $part = preg_split('/<[^>]+>/', $str);

            $date = '';
            $desc = '';
            foreach ($part as $p) {
                if (preg_match('/^\d+\/\d+\/\d+ \d+:\d+:\d+$/', $p))
                    $date = trim($p);
                else
                    $desc .= trim($p);
            }

            if ($date && $desc)
                $result['Traces'][] = ['AcceptTime' => $date, 'AcceptStation' => $desc];

            $start = strpos($response, '<tr data-type=', $end);
            $end = $start ? strpos($response, '</tr>', $start) : false;
        }

        $result['Traces'] = array_reverse($result['Traces']);
        return json_decode(json_encode($result));
    }
}