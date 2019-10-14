<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeclarationAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('declaration_attachments', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('declaration_attachment_file_type',50)->nullable($value = true);
          $table->string('declaration_attachment_file_name',50)->nullable($value = true);
          $table->longText('declaration_attachment_file')->nullable($value = true);
          $table->unsignedInteger('declaration_id');
          $table->foreign('declaration_id')->references('id')->on('declarations')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('declaration_attachments');
    }
}