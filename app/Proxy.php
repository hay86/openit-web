<?php
/**
 * Created by PhpStorm.
 * User: xukf
 * Date: 04/05/2017
 * Time: 8:15 PM
 */

namespace App;

use Log;

class Proxy
{
    public static function curl($url, $post = '', $timeout = 10000, &$error = null, $headers = null) {
        $curl = curl_init($url);
        $result = null;

        if (is_resource($curl))
        {
            curl_setopt_array($curl, [
                CURLOPT_FAILONERROR         => 1,
                CURLOPT_FOLLOWLOCATION      => 1,
                CURLOPT_RETURNTRANSFER      => 1,
                CURLOPT_TCP_NODELAY         => 1,
                CURLOPT_NOSIGNAL            => 1,
                CURLOPT_SSL_VERIFYHOST      => 0,
                CURLOPT_SSL_VERIFYPEER      => 0,
                CURLOPT_BUFFERSIZE          => 10240,
                CURLOPT_TIMEOUT_MS          => $timeout,
                CURLOPT_CONNECTTIMEOUT_MS   => $timeout,
                CURLOPT_HTTP_VERSION        => CURL_HTTP_VERSION_1_0,
            ]);

            if ($post)
            {
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
            }

            if ($headers)
            {
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            }

            $result = curl_exec($curl);

            if (curl_errno($curl) !== 0)
            {
                $error = curl_error($curl);
                Log::error($error);
            }

            curl_close($curl);
        }

        return $result;
    }
}