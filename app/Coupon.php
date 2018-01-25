<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    public $incrementing = false;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function getDiscountAttribute($value) {
        return value_to_price($value);
    }

    public function setDiscountAttribute($price) {
        $this->attributes['discount'] = price_to_value($price);
    }
}
