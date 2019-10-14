<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreviewRegisterCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('preview_register_codes', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('code',20)->nullable($value = true);
          $table->unsignedInteger('system_name_id');
          $table->foreign('system_name_id')->references('id')->on('system_names')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('preview_register_codes');
    }
}