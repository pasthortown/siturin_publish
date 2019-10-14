<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceptionRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('reception_rooms', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('quantity')->nullable($value = true);
          $table->boolean('fullfill')->nullable($value = true);
          $table->unsignedInteger('register_id');
          $table->foreign('register_id')->references('id')->on('registers')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('reception_rooms');
    }
}