<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
          $table->increments('id');
          $table->timestamps();
          $table->string('name',100)->nullable($value = true);
          $table->string('email',255)->nullable($value = true);
          $table->string('password')->nullable($value = true);
          $table->string('address',255)->nullable($value = true);
          $table->float('address_map_latitude',24,16)->nullable($value = true);
          $table->float('address_map_longitude',24,16)->nullable($value = true);
          $table->string('main_phone_number',10)->nullable($value = true);
          $table->string('secondary_phone_number',10)->nullable($value = true);
          $table->string('identification',10)->nullable($value = true);
          $table->string('ruc',13)->nullable($value = true);
          $table->string('api_token',255)->nullable($value = true);
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