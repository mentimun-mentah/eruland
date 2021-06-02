<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_cities', function (Blueprint $table) {
          $table->id();
          $table->string('name');
          $table->string('type');
          $table->bigInteger('shipping_province_id')->unsigned();
        });

        Schema::table('shipping_cities', function (Blueprint $table) {
            $table->foreign('shipping_province_id')->references('id')->on('shipping_provinces')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_cities');
    }
}
