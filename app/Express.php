<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Express extends Model
{
    // status const
    const CANCELED      = 0;
    const SUBMITTED     = 1;
    const CONFIRMED     = 2;
    const PAID          = 3;
    const SERVING       = 4;
    const PACKING       = 5;
    const PICK_UP       = 6;
    const DELIVERED     = 7;
    const RECEIVED      = 8;
    const FINISHED      = 9;
    const DELAYED       = 10;
    const REFUND        = 11;
    const REFUNDED      = 12;

    public function order() {
        return $this->belongsTo('App\Order');
    }

    public function address() {
        return $this->belongsTo('App\Address');
    }

    public function box() {
        return $this->belongsTo('App\Box');
    }

    public function getTrackInfoAttribute($track_info) {
        return json_decode($track_info);
    }

    public function setTrackInfoAttribute($track_info) {
        if (isset($track_info->ShipperCode) && isset($track_info->LogisticCode)) {  // kdn api
            $data = [
                'firm'  => $track_info->ShipperCode,
                'num'   => $track_info->LogisticCode,
                'state' => $track_info->State,
            ];
            $track = [];
            foreach ($track_info->Traces as $trace) {
                $track[] = [
                    'date' => $trace->AcceptTime,
                    'desc' => $trace->AcceptStation,
                ];
            }
            $data['track'] = array_reverse($track);
            $this->attributes['track_info'] = json_encode($data, JSON_UNESCAPED_UNICODE);
        }
    }
}
