<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeclarationItemValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('declaration_item_values', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->double('value',8,2)->nullable($value = true);
          $table->unsignedInteger('declaration_item_id');
          $table->foreign('declaration_item_id')->references('id')->on('declaration_items')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('declaration_item_values');
    }
}