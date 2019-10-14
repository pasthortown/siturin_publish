<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstablishmentPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('establishment_pictures', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('establishment_picture_file_type',50)->nullable($value = true);
          $table->string('establishment_picture_file_name',50)->nullable($value = true);
          $table->longText('establishment_picture_file')->nullable($value = true);
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
       Schema::dropIfExists('establishment_pictures');
    }
}