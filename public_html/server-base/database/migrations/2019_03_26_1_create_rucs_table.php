<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRucsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('rucs', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('number',13)->nullable($value = true);
          $table->boolean('baised_accounting')->nullable($value = true);
          $table->integer('contact_user_id')->nullable($value = true);
          $table->string('owner_name',255)->nullable($value = true);
          $table->unsignedInteger('tax_payer_type_id');
          $table->foreign('tax_payer_type_id')->references('id')->on('tax_payer_types')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('rucs');
    }
}