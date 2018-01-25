<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBoxIdToExpresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expresses', function (Blueprint $table) {
            $table->integer('box_id')->unsigned()->after('address_id');
            $table->foreign('box_id')->references('id')->on('boxes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('expresses', function (Blueprint $table) {
//            $table->dropForeign('expresses_box_id_foreign');
//            $table->dropColumn('box_id');
//        });
    }
}
