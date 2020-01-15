<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('categories', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('name', 50);
      $table->bigInteger('category_id')->unsigned()->nullable();
      $table->timestamps();
    });

    Schema::table('categories', function (Blueprint $table) {
      $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
     });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('categories');
  }
}
