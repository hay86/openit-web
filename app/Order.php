<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $incrementing = false;

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

    // other const
    const REFUND_FEE_RATE       = 0;
    const PACKING_IN_ADVANCE    = 2;

    public function getTotalFeeAttribute($value) {
        return value_to_price($value);
    }

    public function setTotalFeeAttribute($price) {
        $this->attributes['total_fee'] = price_to_value($price);
    }

    public function getCashFeeAttribute($value) {
        return value_to_price($value);
    }

    public function setCashFeeAttribute($price) {
        $this->attributes['cash_fee'] = price_to_value($price);
    }

    public function getExpressFeeAttribute($value) {
        return value_to_price($value);
    }

    public function setExpressFeeAttribute($price) {
        $this->attributes['express_fee'] = price_to_value($price);
    }

    public function getCouponFeeAttribute($value) {
        return value_to_price($value);
    }

    public function getExpressSinceAttribute($value) {
        if (strtotime($value) <= strtotime('Today'))
            return null;
        return $value;
    }

    public function setCouponFeeAttribute($price) {
        $this->attributes['coupon_fee'] = price_to_value($price);
    }

    public function getRefundAttribute() {
        $refund = $this->cash_fee * $this->balance / $this->product->times;
        $refund = $refund * (100 - self::REFUND_FEE_RATE) / 100;

        return value_to_price(price_to_value($refund));
    }

    public function getCashFeeEachBoxAttribute() {
        $cash_fee = $this->cash_fee / $this->product->times;

        return value_to_price(price_to_value($cash_fee));
    }

    public function getExpressDatesAttribute() {
        $timestamps = [];

        foreach ($this->expresses as $express) {
            $packing_ts = strtotime(substr($express->created_at, 0, 10));
            $receive_ts = $packing_ts + self::PACKING_IN_ADVANCE * 24 * 3600;
            if ($express->status !== Express::RECEIVED) {
                while (date('N', $receive_ts) != $this->prefer_day) {
                    $receive_ts += 24 * 3600;
                }
            }
            $timestamps[$receive_ts] = true;
        }

        if (count($timestamps) === 0 && $this->balance === 0) {
            if ($this->status === Order::CANCELED || $this->status === Order::REFUNDED || $this->status === Order::FINISHED) {
                $balance = 0;
            }
            else {
                $balance = $this->product->times;
            }
        }
        else {
            if ($this->status === Order::PICK_UP) {
                $balance = $this->balance - 1;
            }
            else {
                $balance = $this->balance;
            }
        }

        if ($balance > 0) {
            $next_ts    = express_date($this->prefer_day, null, 0);
            $first_ts   = express_date($this->prefer_day, $this->created_at->timestamp);
            $delay_ts   = ($this->status == self::DELAYED) ?
                          express_date($this->prefer_day, strtotime($this->express_since)) : 0;

            $next_ts = max($next_ts, $first_ts, $delay_ts);
            if (!isset($timestamps[$next_ts])) {
                $timestamps[$next_ts] = true;
                $balance --;
            }
        }

        while ($balance > 0) {
            $next_ts = express_date($this->prefer_day, $next_ts);
            if (!isset($timestamps[$next_ts])) {
                $timestamps[$next_ts] = true;
                $balance--;
            }
        }

        $timestamps = array_keys($timestamps);
        sort($timestamps);

        return $timestamps;
    }

    public function getNextExpressAttribute() {
        if ($this->status == self::CANCELED ||
            $this->status == self::FINISHED ||
            $this->status == self::REFUNDED)
            return 0;

        $timestamps = $this->express_dates;
        $today_ts = strtotime('Today');

        for ($i=0; $i<count($timestamps); $i++)
            if ($timestamps[$i] > $today_ts)
                return $timestamps[$i];

        return 0;
    }

    public function getNextExpressNoAttribute() {
        if ($this->status == self::CANCELED ||
            $this->status == self::FINISHED ||
            $this->status == self::REFUNDED)
            return 0;

        $timestamps = $this->express_dates;
        $today_ts = strtotime('Today');

        for ($i=0; $i<count($timestamps); $i++)
            if ($timestamps[$i] > $today_ts)
                return $i + 1;

        return 0;
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function product() {
        return $this->belongsTo('App\Product')->withTrashed();
    }

    public function address() {
        return $this->belongsTo('App\Address');
    }

    public function coupon() {
        return $this->belongsTo('App\Coupon')->withTrashed();
    }

    public function express() {
        return $this->belongsTo('App\Express');
    }

    public function expresses() {
        return $this->hasMany('App\Express');
    }
}
