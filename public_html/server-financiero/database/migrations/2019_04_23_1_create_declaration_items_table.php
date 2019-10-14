<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeclarationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('declaration_items', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('name',1024)->nullable($value = true);
          $table->longText('description')->nullable($value = true);
          $table->integer('factor')->nullable($value = true);
          $table->integer('year')->nullable($value = true);
          $table->integer('tax_payer_type_id')->nullable($value = true);
          $table->unsignedInteger('declaration_item_category_id');
          $table->foreign('declaration_item_category_id')->references('id')->on('declaration_item_categories')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('declaration_items');
    }
}