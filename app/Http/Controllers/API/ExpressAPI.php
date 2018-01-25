<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Tracking;
use App\Express;
use App\Order;

class ExpressAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function show($id)
    {
        $express = Express::find($id);

        if (!$express) {
            return response()->json([
                'status'    => 'failed',
                'reason'    => 'express not found',
            ]);
        }

        if ($express->status == Order::DELIVERED) {
            $code = courier_firm($express->courier_firm)['code'];
            $num = $express->courier_num;

            if ($code === 'ZT') {
                $express->order->status = Order::RECEIVED;
                $express->order->save();
                $express->status = Order::RECEIVED;
                $express->save();
            }
            else {
                $track = new Tracking;
                $track_info = $track->getTrackInfoByKDN($code, $num);

                if ($track_info && $track_info->Success) {
                    if ($track_info->State == 3) {
                        $express->status = Order::RECEIVED;
                        $express->order->status = Order::RECEIVED;
                        $express->order->save();
                    }

                    $express->track_info = $track_info;
                    $express->save();
                }
            }
        }

        return response()->json([
            'status'    => 'success',
            'track_info'=> $express->track_info,
        ]);
    }

    public function holidays($year)
    {
        return response()->json([
            'status'    => 'success',
            'holidays'  => holidays($year),
        ]);
    }
}
