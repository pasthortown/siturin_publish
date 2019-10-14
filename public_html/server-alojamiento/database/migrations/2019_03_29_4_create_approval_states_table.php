<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovalStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('approval_states', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->boolean('value')->nullable($value = true);
          $table->dateTime('date_assigment')->nullable($value = true);
          $table->longText('notes')->nullable($value = true);
          $table->integer('id_user')->nullable($value = true);
          $table->dateTime('date_fullfill')->nullable($value = true);
          $table->unsignedInteger('register_id');
          $table->foreign('register_id')->references('id')->on('registers')->onDelete('cascade');
          $table->unsignedInteger('approval_id');
          $table->foreign('approval_id')->references('id')->on('approvals')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('approval_states');
    }
}