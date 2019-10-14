<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('pay_taxes', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('year')->nullable($value = true);
          $table->integer('trimester')->nullable($value = true);
          $table->double('value',8,2)->nullable($value = true);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('pay_taxes');
    }
}