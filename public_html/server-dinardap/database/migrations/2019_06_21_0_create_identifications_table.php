<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdentificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('identifications', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('number',10)->nullable($value = true);
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
       Schema::dropIfExists('identifications');
    }
}