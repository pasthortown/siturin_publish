<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayMassFileAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('pay_mass_file_attachments', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('pay_mass_file_attachment_file_type',50)->nullable($value = true);
          $table->string('pay_mass_file_attachment_file_name',50)->nullable($value = true);
          $table->longText('pay_mass_file_attachment_file')->nullable($value = true);
          $table->dateTime('date')->nullable($value = true);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('pay_mass_file_attachments');
    }
}