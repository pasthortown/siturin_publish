<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCapacityPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('capacity_pictures', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('capacity_picture_file_type',50)->nullable($value = true);
          $table->string('capacity_picture_file_name',50)->nullable($value = true);
          $table->longText('capacity_picture_file')->nullable($value = true);
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
       Schema::dropIfExists('capacity_pictures');
    }
}