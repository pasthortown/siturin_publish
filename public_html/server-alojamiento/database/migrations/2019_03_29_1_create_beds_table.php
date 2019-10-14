<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('beds', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('quantity')->nullable($value = true);
          $table->unsignedInteger('bed_type_id');
          $table->foreign('bed_type_id')->references('id')->on('bed_types')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('beds');
    }
}