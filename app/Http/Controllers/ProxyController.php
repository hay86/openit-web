<?php
/**
 * Created by PhpStorm.
 * User: xukf
 * Date: 04/05/2017
 * Time: 5:03 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proxy;
use Log;

class ProxyController extends Controller
{
    public function ip(Request $request) {
        $key = '694b31dee439faaf008cf323c53b150d';
        $url = 'http://restapi.amap.com/v3/ip?key=' . $key . '&ip=' . $request->ip();

        $err = '';
        $res = json_decode(Proxy::curl($url, '', 1000, $err));

        if ($res && $res->province && $res->city) {
            return response()->json([
                'status'    => 'success',
                'province'  => $res->province,
                'city'      => $res->city,
            ]);
        }
        else {
            return response()->json([
                'status'    => 'failed',
                'reason'    => $err,
            ]);
        }
    }
}