<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcedureJustificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('procedure_justifications', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->longText('justification')->nullable($value = true);
          $table->unsignedInteger('procedure_id');
          $table->foreign('procedure_id')->references('id')->on('procedures')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('procedure_justifications');
    }
}