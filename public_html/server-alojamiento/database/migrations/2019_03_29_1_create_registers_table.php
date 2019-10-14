<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('registers', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('code',50)->nullable($value = true);
          $table->boolean('autorized_complementary_capacities')->nullable($value = true);
          $table->integer('establishment_id')->nullable($value = true);
          $table->boolean('autorized_complementary_food_capacities')->nullable($value = true);
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
       Schema::dropIfExists('registers');
    }
}