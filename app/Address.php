<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function getContactStringAttribute() {
        return implode(' ', [$this->username, $this->mobile]);
    }

    public function getAddressStringAttribute() {
        return implode('', [$this->province, $this->city, $this->district, $this->detail]);
    }
}
