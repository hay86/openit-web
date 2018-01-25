<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->integer('id')->unsigned()->primary();
            $table->integer('s0')->unsigned()->default(0);
            $table->integer('s1')->unsigned()->default(0);
            $table->integer('s2')->unsigned()->default(0);
            $table->integer('s3')->unsigned()->default(0);
            $table->integer('s4')->unsigned()->default(0);
            $table->integer('s5')->unsigned()->default(0);
            $table->integer('s6')->unsigned()->default(0);
            $table->integer('s7')->unsigned()->default(0);
            $table->integer('s8')->unsigned()->default(0);
            $table->integer('s9')->unsigned()->default(0);
            $table->integer('s10')->unsigned()->default(0);
            $table->integer('s11')->unsigned()->default(0);
            $table->integer('s12')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('statuses');
    }
}
