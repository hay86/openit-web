<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    public function products() {
        return $this->belongsToMany('App\Product')->withTrashed()->withPivot('box_id', 'product_id', 'unit', 'stock', 'total_stock', 'total_cost')->orderBy('price', 'desc');
    }

    public function product($id) {
        return $this->belongsToMany('App\Product')->wherePivot('product_id', $id)->withTrashed()->withPivot('box_id', 'product_id', 'unit', 'stock', 'total_stock', 'total_cost');
    }

    public function getStockAttribute() {
        $min_stock = 9999;
        foreach ($this->products as $product) {
            $min_stock = min($min_stock, intval($product->stock/$product->unit));
        }
        return $min_stock;
    }

    public function getCostAttribute() {
        $cost = 0;
        foreach ($this->products as $product) {
            $cost += $product->unit * $product->unit_cost;
        }
        return $cost;
    }

    public function getPriceAttribute() {
        $price = 0;
        foreach ($this->products as $product) {
            $price += $product->unit * $product->price;
        }
        return $price;
    }
}
