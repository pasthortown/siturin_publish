<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovalStateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('approval_state_attachments', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('approval_state_attachment_file_type',50)->nullable($value = true);
          $table->string('approval_state_attachment_file_name',200)->nullable($value = true);
          $table->longText('approval_state_attachment_file')->nullable($value = true);
          $table->unsignedInteger('approval_state_id');
          $table->foreign('approval_state_id')->references('id')->on('approval_states')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('approval_state_attachments');
    }
}