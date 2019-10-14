<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyTitleAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('property_title_attachments', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('property_title_attachment_file_type',50)->nullable($value = true);
          $table->string('property_title_attachment_file_name',500)->nullable($value = true);
          $table->longText('property_title_attachment_file')->nullable($value = true);
          $table->unsignedInteger('register_id');
          $table->foreign('register_id')->references('id')->on('registers')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('property_title_attachments');
    }
}