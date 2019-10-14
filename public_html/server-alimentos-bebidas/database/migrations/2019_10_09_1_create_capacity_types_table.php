<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCapacityTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('capacity_types', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('name',50)->nullable($value = true);
          $table->longText('description')->nullable($value = true);
          $table->integer('group_quantity')->nullable($value = true);
          $table->boolean('is_island')->nullable($value = true);
          $table->integer('spaces')->nullable($value = true);
          $table->boolean('editable_groups')->nullable($value = true);
          $table->boolean('editable_spaces')->nullable($value = true);
          $table->unsignedInteger('register_type_id');
          $table->foreign('register_type_id')->references('id')->on('register_types')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('capacity_types');
    }
}