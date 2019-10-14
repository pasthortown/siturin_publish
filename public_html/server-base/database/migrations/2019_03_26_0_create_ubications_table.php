<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUbicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('ubications', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('name',255)->nullable($value = true);
          $table->string('code',100)->nullable($value = true);
          $table->string('father_code',100)->nullable($value = true);
          $table->float('gmap_reference_latitude',24,16)->nullable($value = true);
          $table->float('gmap_reference_longitude',24,16)->nullable($value = true);
          $table->string('acronym',20)->nullable($value = true);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('ubications');
    }
}