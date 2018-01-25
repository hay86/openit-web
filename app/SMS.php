<?php
/**
 * Created by PhpStorm.
 * User: xukf
 * Date: 10/05/2017
 * Time: 9:15 AM
 */

namespace App;


class SMS
{
    public static function deliveryNotificationByAliyun($mobile, $name, $time) {
        $tpl = 'SMS_66625148';

        return self::notificationByAliyun($tpl, [
            'FreeSignName'  => 'Openit零食',
            'TemplateCode'  => $tpl,
            'Type'          => 'singleContent',
            'Receiver'      => $mobile,
            'SmsParams'     => json_encode(['name' => $name, 'time' => $time])
        ]);
    }

    public static function delayNotificationByAliyun($mobile, $name, $time) {
        $tpl = 'SMS_70165651';

        return self::notificationByAliyun($tpl, [
            'FreeSignName'  => 'Openit零食',
            'TemplateCode'  => $tpl,
            'Type'          => 'singleContent',
            'Receiver'      => $mobile,
            'SmsParams'     => json_encode(['name' => $name, 'time' => $time])
        ]);
    }

    private static function notificationByAliyun($tpl, $param) {
        // generate xml
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><Message xmlns="http://mns.aliyuncs.com/doc/v1/"></Message>');
        $xml->addChild('MessageBody', $tpl);
        $xml->addChild('MessageAttributes')->addChild('DirectSMS', json_encode($param));
        $xml = $xml->asXML();

        // generate signature
        $host   = '1933889418021136.mns.cn-shenzhen.aliyuncs.com';
        $query  = '/topics/sms.topic-cn-shenzhen/messages';
        $date   = gmdate('D, d M Y H:i:s') . ' GTM';
        $type   = 'text/xml;charset=utf-8';
        $ver    = 'x-mns-version:2015-06-06';
        $signature = base64_encode(hash_hmac('sha1',
                "POST\n\n" . $type . "\n" . $date . "\n" . $ver . "\n" . $query,
                env('OOS_ACCESS_KEY_SECRET'), true)
        );

        // send sms
        $header = "POST " . $query . " HTTP/1.1\r\n";
        $header .= "Host:" . $host . "\r\n";
        $header .= "Date:" . $date . "\r\n";
        $header .= "Content-Length:" . strlen($xml) . "\r\n";
        $header .= "Content-Type:" . $type . "\r\n";
        $header .= "Authorization:MNS " . env('OOS_ACCESS_KEY_ID') . ':' . $signature . "\r\n";
        $header .= $ver . "\r\n";
        $header .= "Connection:close\r\n\r\n";
        $header .= $xml;

        $fd = fsockopen($host, 80);
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

        return $output;
    }
}