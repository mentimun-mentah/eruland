<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
          $table->id();
          $table->integer('qty');
          $table->bigInteger('price');
          $table->string('code_shipping');
          $table->string('service_shipping');
          $table->bigInteger('cost_shipping');
          $table->string('etd_shipping');
          $table->integer('rating')->nullable();
          $table->text('review')->nullable();
          $table->string('no_resi')->nullable();
          $table->string('drawn',10)->default('no');
          $table->bigInteger('order_id')->unsigned();
          $table->bigInteger('penjual_id')->unsigned();
          $table->bigInteger('product_id')->unsigned();
          $table->timestamps();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('penjual_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
