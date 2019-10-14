<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstablishmentCertificationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('establishment_certification_types', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('name',100)->nullable($value = true);
          $table->string('code',20)->nullable($value = true);
          $table->string('father_code',20)->nullable($value = true);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('establishment_certification_types');
    }
}