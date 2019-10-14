<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstablishmentCertificationAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('establishment_certification_attachments', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('establishment_certification_attachment_file_type',50)->nullable($value = true);
          $table->string('establishment_certification_attachment_file_name',50)->nullable($value = true);
          $table->longText('establishment_certification_attachment_file')->nullable($value = true);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('establishment_certification_attachments');
    }
}