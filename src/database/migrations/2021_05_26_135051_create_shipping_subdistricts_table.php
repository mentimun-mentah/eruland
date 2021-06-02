<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingSubdistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_subdistricts', function (Blueprint $table) {
          $table->id();
          $table->string('name');
          $table->bigInteger('shipping_city_id')->unsigned();
        });

        Schema::table('shipping_subdistricts', function (Blueprint $table) {
            $table->foreign('shipping_city_id')->references('id')->on('shipping_cities')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_subdistricts');
    }
}
