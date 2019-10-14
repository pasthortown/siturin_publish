<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisterRequisitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('register_requisites', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->boolean('fullfill')->nullable($value = true);
          $table->string('value',2048)->nullable($value = true);
          $table->unsignedInteger('requisite_id');
          $table->foreign('requisite_id')->references('id')->on('requisites')->onDelete('cascade');
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
       Schema::dropIfExists('register_requisites');
    }
}