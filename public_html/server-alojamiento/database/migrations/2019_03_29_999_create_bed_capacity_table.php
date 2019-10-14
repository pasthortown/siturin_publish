<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBedCapacityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('bed_capacity', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->unsignedInteger('bed_id');
          $table->foreign('bed_id')->references('id')->on('beds')->onDelete('cascade');
          $table->unsignedInteger('capacity_id');
          $table->foreign('capacity_id')->references('id')->on('capacities')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('bed_capacity');
    }
}
