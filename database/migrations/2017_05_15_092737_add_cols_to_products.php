<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image_id')->nullable()->after('name');
            $table->string('country')->nullable()->after('name');
            $table->smallInteger('life')->unsigned()->default(0)->after('times');
            $table->smallInteger('weight')->unsigned()->default(0)->after('times');
            $table->smallInteger('height')->unsigned()->default(0)->after('times');
            $table->smallInteger('width')->unsigned()->default(0)->after('times');
            $table->smallInteger('length')->unsigned()->default(0)->after('times');
            $table->tinyInteger('hardness')->default(0)->after('times');
            $table->tinyInteger('sweetness')->default(0)->after('times');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
//            $table->dropColumn('image_id');
//            $table->dropColumn('country');
//            $table->dropColumn('life');
//            $table->dropColumn('weight');
//            $table->dropColumn('height');
//            $table->dropColumn('width');
//            $table->dropColumn('length');
//            $table->dropColumn('hardness');
//            $table->dropColumn('sweetness');
        });
    }
}
