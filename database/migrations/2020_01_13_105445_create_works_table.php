<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('works', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('service_id')->unsigned();
      $table->bigInteger('other_user_id')->unsigned();
      $table->enum('status', ['pending', 'compeleted', 'canceled'])->default('pending');
      $table->enum('payment_method', ['wurqe', 'cash'])->default('wurqe');
      $table->decimal('price', 9,3)->nullable();
      $table->string('price_currency')->default('$');
      $table->dateTime('completed_at')->nullable();
      $table->timestamps();
    });

    Schema::table('works', function (Blueprint $table) {
      $table->foreign('other_user_id')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('works');
  }
}
