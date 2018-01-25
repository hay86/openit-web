<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('courier_num');
            $table->tinyInteger('courier_firm');
            $table->tinyInteger('prefer_day')->unsigned();
            $table->tinyInteger('status')->unsigned();
            $table->bigInteger('order_id')->unsigned();
            $table->integer('address_id')->unsigned();
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->text('track_info');
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
//        Schema::dropIfExists('expresses');
    }
}
