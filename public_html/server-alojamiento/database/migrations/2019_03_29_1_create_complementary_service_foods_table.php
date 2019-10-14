<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplementaryServiceFoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('complementary_service_foods', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('quantity_tables')->nullable($value = true);
          $table->integer('quantity_chairs')->nullable($value = true);
          $table->unsignedInteger('complementary_service_food_type_id');
          $table->foreign('complementary_service_food_type_id')->references('id')->on('complementary_service_food_types')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('complementary_service_foods');
    }
}