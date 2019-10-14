<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('registers', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('ruc',100)->nullable($value = true);
          $table->string('comercial_name',500)->nullable($value = true);
          $table->string('register_code',100)->nullable($value = true);
          $table->date('as_turistic_date')->nullable($value = true);
          $table->string('activity',100)->nullable($value = true);
          $table->string('category',100)->nullable($value = true);
          $table->string('classification',100)->nullable($value = true);
          $table->string('legal_representant_name',500)->nullable($value = true);
          $table->string('legal_representant_identification',100)->nullable($value = true);
          $table->string('establishment_property_type',100)->nullable($value = true);
          $table->string('organization_type',100)->nullable($value = true);
          $table->string('ubication_main',255)->nullable($value = true);
          $table->string('ubication_sencond',255)->nullable($value = true);
          $table->string('ubication_third',255)->nullable($value = true);
          $table->string('address',1024)->nullable($value = true);
          $table->string('main_phone_number',20)->nullable($value = true);
          $table->string('secondary_phone_number',20)->nullable($value = true);
          $table->string('email',1024)->nullable($value = true);
          $table->string('web',1024)->nullable($value = true);
          $table->string('system_source',100)->nullable($value = true);
          $table->float('georeference_latitude',24,16)->nullable($value = true);
          $table->float('georeference_longitude',24,16)->nullable($value = true);
          $table->string('establishment_ruc_code',5)->nullable($value = true);
          $table->integer('max_capacity')->nullable($value = true);
          $table->integer('max_areas')->nullable($value = true);
          $table->integer('total_male')->nullable($value = true);
          $table->integer('total_female')->nullable($value = true);
          $table->string('ruc_state',100)->nullable($value = true);
          $table->integer('max_beds')->nullable($value = true);
          $table->string('establishment_state',100)->nullable($value = true);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('registers');
    }
}
