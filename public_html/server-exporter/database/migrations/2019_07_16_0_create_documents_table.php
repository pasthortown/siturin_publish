<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('documents', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->longText('params')->nullable($value = true);
          $table->string('code',500)->nullable($value = true);
          $table->string('procedure_id',100)->nullable($value = true);
          $table->string('activity',100)->nullable($value = true);
          $table->string('zonal',100)->nullable($value = true);
          $table->string('document_type',100)->nullable($value = true);
          $table->string('user',10)->nullable($value = true);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('documents');
    }
}