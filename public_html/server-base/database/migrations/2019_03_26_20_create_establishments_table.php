<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstablishmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('establishments', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('ruc_code_id',3)->nullable($value = true);
          $table->string('commercially_known_name',255)->nullable($value = true);
          $table->string('address_main_street',255)->nullable($value = true);
          $table->float('address_map_latitude',24,16)->nullable($value = true);
          $table->float('address_map_longitude',24,16)->nullable($value = true);
          $table->string('url_web',255)->nullable($value = true);
          $table->dateTime('as_turistic_register_date')->nullable($value = true);
          $table->longText('address_reference')->nullable($value = true);
          $table->integer('contact_user_id')->nullable($value = true);
          $table->string('address_secondary_street',255)->nullable($value = true);
          $table->string('address_number',20)->nullable($value = true);
          $table->string('franchise_chain_name',50)->nullable($value = true);
          $table->unsignedInteger('ruc_id');
          $table->foreign('ruc_id')->references('id')->on('rucs')->onDelete('cascade');
          $table->unsignedInteger('ubication_id');
          $table->foreign('ubication_id')->references('id')->on('ubications')->onDelete('cascade');
          $table->unsignedInteger('establishment_property_type_id');
          $table->foreign('establishment_property_type_id')->references('id')->on('establishment_property_types')->onDelete('cascade');
          $table->unsignedInteger('ruc_name_type_id');
          $table->foreign('ruc_name_type_id')->references('id')->on('ruc_name_types')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('establishments');
    }
}