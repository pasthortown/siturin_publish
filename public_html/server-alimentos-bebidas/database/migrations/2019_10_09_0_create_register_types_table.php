<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisterTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('register_types', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('name',100)->nullable($value = true);
          $table->longText('description')->nullable($value = true);
          $table->string('code',100)->nullable($value = true);
          $table->string('father_code',100)->nullable($value = true);
          $table->integer('min_points')->nullable($value = true);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('register_types');
    }
}