<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeclarationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('declarations', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('establishment_id')->nullable($value = true);
          $table->dateTime('declaration_date')->nullable($value = true);
          $table->integer('year')->nullable($value = true);
          $table->dateTime('max_date_to_pay')->nullable($value = true);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('declarations');
    }
}