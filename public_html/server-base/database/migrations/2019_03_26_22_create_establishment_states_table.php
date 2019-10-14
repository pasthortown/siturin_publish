<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstablishmentStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('establishment_states', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->longText('justification')->nullable($value = true);
          $table->unsignedInteger('state_id');
          $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
          $table->unsignedInteger('establishment_id');
          $table->foreign('establishment_id')->references('id')->on('establishments')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('establishment_states');
    }
}