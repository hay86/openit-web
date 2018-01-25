<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function articles() {
        return $this->hasMany('App\Article')->orderBy('created_at', 'desc');
    }

    public function address() {
        return $this->belongsTo('App\Address');
    }

    public function addresses() {
        return $this->hasMany('App\Address')->orderBy('id', 'desc');
    }

    public function coupons() {
        return $this->hasMany('App\Coupon')->orderBy('discount', 'desc');
    }

    public function orders() {
        return $this->hasMany('App\Order')->orderBy('id', 'desc');
    }

    public function isAdmin() {
        return strstr($this->email, '@') === strstr(env('MAIL_USERNAME'), '@');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
