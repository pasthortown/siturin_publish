<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCapacityTariffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('capacity_tariff', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->unsignedInteger('tariff_id');
          $table->foreign('tariff_id')->references('id')->on('tariffs')->onDelete('cascade');
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
       Schema::dropIfExists('capacity_tariff');
    }
}