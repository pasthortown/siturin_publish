<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCapacitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('capacities', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('quantity')->nullable($value = true);
          $table->integer('max_groups')->nullable($value = true);
          $table->integer('max_spaces')->nullable($value = true);
          $table->unsignedInteger('capacity_type_id');
          $table->foreign('capacity_type_id')->references('id')->on('capacity_types')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('capacities');
    }
}