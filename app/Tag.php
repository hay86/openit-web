<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function articles() {
        return $this->belongsToMany('App\Article')->orderBy('created_at', 'desc');
    }
}
