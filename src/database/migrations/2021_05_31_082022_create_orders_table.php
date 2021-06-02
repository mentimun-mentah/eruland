<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
          $table->id();
          $table->string('bank',20);
          $table->bigInteger('gross_amount');
          $table->bigInteger('total_cost_shipping');
          $table->string('order_id');
          $table->string('payment_type');
          $table->string('pdf_url');
          $table->string('transaction_status');
          $table->string('va_number');
          $table->bigInteger('user_id')->unsigned();
          $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
