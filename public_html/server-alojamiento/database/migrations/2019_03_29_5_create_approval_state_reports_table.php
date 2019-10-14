<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovalStateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('approval_state_reports', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->longText('background')->nullable($value = true);
          $table->longText('actions_done')->nullable($value = true);
          $table->longText('conclution')->nullable($value = true);
          $table->longText('recomendation')->nullable($value = true);
          $table->unsignedInteger('approval_state_id');
          $table->foreign('approval_state_id')->references('id')->on('approval_states')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('approval_state_reports');
    }
}