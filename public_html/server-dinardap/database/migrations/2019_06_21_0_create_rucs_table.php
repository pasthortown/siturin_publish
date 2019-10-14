<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRucsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('rucs', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('number',13)->nullable($value = true);
          $table->longText('data')->nullable($value = true);
          $table->dateTime('date')->nullable($value = true);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('rucs');
    }
}