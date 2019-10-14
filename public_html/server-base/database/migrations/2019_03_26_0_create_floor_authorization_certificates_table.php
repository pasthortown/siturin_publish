<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFloorAuthorizationCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('floor_authorization_certificates', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('floor_authorization_certificate_file_type',50)->nullable($value = true);
          $table->string('floor_authorization_certificate_file_name',50)->nullable($value = true);
          $table->longText('floor_authorization_certificate_file')->nullable($value = true);
          $table->integer('register_id')->nullable($value = true);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('floor_authorization_certificates');
    }
}