<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStateDeclarationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('state_declarations', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->longText('justification')->nullable($value = true);
          $table->dateTime('moment')->nullable($value = true);
          $table->unsignedInteger('declaration_id');
          $table->foreign('declaration_id')->references('id')->on('declarations')->onDelete('cascade');
          $table->unsignedInteger('state_id');
          $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('state_declarations');
    }
}