<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeclarationItemCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('declaration_item_categories', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('name',250)->nullable($value = true);
          $table->longText('description')->nullable($value = true);
          $table->integer('year')->nullable($value = true);
          $table->integer('tax_payer_type_id')->nullable($value = true);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('declaration_item_categories');
    }
}