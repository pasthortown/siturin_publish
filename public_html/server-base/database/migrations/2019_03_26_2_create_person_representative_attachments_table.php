<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonRepresentativeAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('person_representative_attachments', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('person_representative_attachment_file_type',50)->nullable($value = true);
          $table->string('person_representative_attachment_file_name',50)->nullable($value = true);
          $table->longText('person_representative_attachment_file')->nullable($value = true);
          $table->string('ruc',13)->nullable($value = true);
          $table->date('assignment_date')->nullable($value = true);
          $table->unsignedInteger('person_representative_id');
          $table->foreign('person_representative_id')->references('id')->on('person_representatives')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('person_representative_attachments');
    }
}