<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('workers', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('count')->nullable($value = true);
          $table->unsignedInteger('gender_id');
          $table->foreign('gender_id')->references('id')->on('genders')->onDelete('cascade');
          $table->unsignedInteger('worker_group_id');
          $table->foreign('worker_group_id')->references('id')->on('worker_groups')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('workers');
    }
}