<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupGivensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('group_givens', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('register_code',50)->nullable($value = true);
          $table->unsignedInteger('ruc_id');
          $table->foreign('ruc_id')->references('id')->on('rucs')->onDelete('cascade');
          $table->unsignedInteger('person_representative_id');
          $table->foreign('person_representative_id')->references('id')->on('person_representatives')->onDelete('cascade');
          $table->unsignedInteger('group_type_id');
          $table->foreign('group_type_id')->references('id')->on('group_types')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('group_givens');
    }
}