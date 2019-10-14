<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('logs', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->dateTime('date_time')->nullable($value = true);
          $table->longText('request')->nullable($value = true);
          $table->unsignedInteger('user_id');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('logs');
    }
}