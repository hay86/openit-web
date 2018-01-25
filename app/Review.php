<?php
/**
 * Created by PhpStorm.
 * User: xukf
 * Date: 28/05/2017
 * Time: 9:20 AM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function product() {
        return $this->belongsTo('App\Product')->withTrashed();
    }

    public function order() {
        return $this->belongsTo('App\Order');
    }
}