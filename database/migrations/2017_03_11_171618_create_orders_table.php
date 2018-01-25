<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary();
            $table->integer('total_fee')->unsigned();
            $table->integer('cash_fee')->unsigned();
            $table->integer('express_fee')->unsigned();
            $table->integer('coupon_fee')->unsigned();
            $table->tinyInteger('prefer_day')->unsigned()->index(); // 0: delay, 1-7: Mon-Sun
            $table->tinyInteger('balance')->unsigned()->index(); // 0: order complete, >0: under going
            $table->tinyInteger('status')->unsigned()->index(); // ref to helper.php
            $table->string('trade_type')->nullable(); // JSAPI, NATIVE, APP
            $table->string('trade_mch')->nullable(); // merchant id
            $table->string('trade_bank')->nullable(); // payment bank
            $table->timestamp('trade_time')->nullable(); // payment time
            $table->string('invoice')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');
            $table->integer('address_id')->unsigned();
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->integer('express_id')->unsigned()->nullable();
            $table->foreign('express_id')->references('id')->on('expresses');
            $table->string('coupon_id')->nullable();
            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('orders');
    }
}
