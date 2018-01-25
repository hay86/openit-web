<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Product extends Model
{
    use SoftDeletes;

    const RETAIL    = 0;
    const PACK      = 1;

    protected $dates = ['deleted_at'];

    public function getPriceAttribute($value) {
        return value_to_price($value);
    }

    public function setPriceAttribute($price) {
        $this->attributes['price'] = price_to_value($price);
    }

    public function getDisplayNameAttribute() {
        if ($this->country)
            return '【' . $this->country . '】' . $this->name;
        return $this->name;
    }

    public function article() {
        return $this->belongsTo('App\Article')->withTrashed();
    }

    public function getUnitAttribute() {
        return $this->pivot->unit;
    }

    public function getStockAttribute() {
        return $this->pivot->stock;
    }

    public function getUnitCostAttribute() {
        return $this->pivot->total_stock == 0 ?
            0 : value_to_price($this->pivot->total_cost / $this->pivot->total_stock);
    }

    public function getTotalStockAttribute() {
        return $this->pivot->total_stock;
    }

    public function getTotalCostAttribute() {
        return value_to_price($this->pivot->total_cost);
    }

    public function getThumbnailAttribute() {
        return  Image::gen_md_wide_url($this->image_id);
    }

    public function incrementStock($amount) {
        $db = DB::table('box_product')->where(['box_id' => $this->pivot->box_id, 'product_id' => $this->id]);
        $db->increment('stock', $amount);
        $db->increment('total_stock', $amount);
    }

    public function incrementCost($amount) {
        $db = DB::table('box_product')->where(['box_id' => $this->pivot->box_id, 'product_id' => $this->id]);
        $db->increment('total_cost', price_to_value($amount));
    }

    public function decrementStockByUnit($amount = 1) {
        $db = DB::table('box_product')->where(['box_id' => $this->pivot->box_id, 'product_id' => $this->id]);
        $db->decrement('stock', $amount * $this->pivot->unit);
    }

    public function incrementStockByUnit($amount = 1) {
        $db = DB::table('box_product')->where(['box_id' => $this->pivot->box_id, 'product_id' => $this->id]);
        $db->increment('stock', $amount * $this->pivot->unit);
    }
}
