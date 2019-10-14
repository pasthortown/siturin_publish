<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('pay_attachments', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('pay_attachment_file_type',50)->nullable($value = true);
          $table->string('pay_attachment_file_name',50)->nullable($value = true);
          $table->longText('pay_attachment_file')->nullable($value = true);
          $table->unsignedInteger('pay_id');
          $table->foreign('pay_id')->references('id')->on('pays')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('pay_attachments');
    }
}