<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisterProceduresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('register_procedures', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->dateTime('date')->nullable($value = true);
          $table->unsignedInteger('register_id');
          $table->foreign('register_id')->references('id')->on('registers')->onDelete('cascade');
          $table->unsignedInteger('procedure_justification_id');
          $table->foreign('procedure_justification_id')->references('id')->on('procedure_justifications')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('register_procedures');
    }
}