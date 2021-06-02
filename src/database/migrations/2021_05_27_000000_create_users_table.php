<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('fullname')->nullable();
            $table->string('phone',20)->nullable();
            $table->string('password');
            $table->string('gender',20)->nullable();
            $table->string('ktp')->nullable();
            $table->string('avatar')->default('default.png');
            $table->string('role',20)->default('pengguna');
            $table->string('token');
            $table->tinyInteger('status')->default(0);
            $table->text('address')->nullable();
            $table->string('no_rek')->unique()->nullable();
            $table->string('a_n')->nullable();
            $table->integer('code_pos')->nullable();
            $table->bigInteger('shipping_subdistrict_id')->unsigned()->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('shipping_subdistrict_id')->references('id')->on('shipping_subdistricts')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
