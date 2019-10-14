<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstablishmentCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('establishment_certifications', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->unsignedInteger('establishment_certification_type_id');
          $table->foreign('establishment_certification_type_id')->references('id')->on('establishment_certification_types')->onDelete('cascade');
          $table->unsignedInteger('establishment_certification_attachment_id');
          $table->foreign('establishment_certification_attachment_id')->references('id')->on('establishment_certification_attachments')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('establishment_certifications');
    }
}