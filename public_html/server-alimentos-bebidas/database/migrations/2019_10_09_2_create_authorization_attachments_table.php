<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorizationAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('authorization_attachments', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('authorization_attachment_file_type',50)->nullable($value = true);
          $table->string('authorization_attachment_file_name',500)->nullable($value = true);
          $table->longText('authorization_attachment_file')->nullable($value = true);
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
       Schema::dropIfExists('authorization_attachments');
    }
}