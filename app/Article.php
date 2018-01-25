<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function image() {
        return $this->belongsTo('App\Image');
    }

    public function images() {
        return $this->hasMany('App\Image');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function tags(){
        return $this->belongsToMany('App\Tag');
    }

    public function img_xs() {
        return  Image::gen_xs_url($this->image_id);
    }

    public function img_sm() {
        return  Image::gen_sm_url($this->image_id);
    }

    public function img_md() {
        return  Image::gen_md_url($this->image_id);
    }

    public function img_lg() {
        return  Image::gen_lg_url($this->image_id);
    }

    public function img_xs_wide() {
        return  Image::gen_xs_wide_url($this->image_id);
    }

    public function img_sm_wide() {
        return  Image::gen_sm_wide_url($this->image_id);
    }

    public function img_md_wide() {
        return  Image::gen_md_wide_url($this->image_id);
    }

    public function img_lg_wide() {
        return  Image::gen_lg_wide_url($this->image_id);
    }

    public function img_xs_sqr() {
        return  Image::gen_xs_sqr_url($this->image_id);
    }

    public function img_sm_sqr() {
        return  Image::gen_sm_sqr_url($this->image_id);
    }

    public function img_md_sqr() {
        return  Image::gen_md_sqr_url($this->image_id);
    }

    public function img_lg_sqr() {
        return  Image::gen_lg_sqr_url($this->image_id);
    }
}
