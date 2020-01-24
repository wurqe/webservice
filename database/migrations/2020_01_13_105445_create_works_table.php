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
      $table->bigInteger('invitation_id')->unsigned();
      $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending');
      // $table->enum('payment_method', ['wurqe', 'cash'])->default('wurqe');
      $table->decimal('amount', 9,3)->nullable();
      $table->string('amount_currency')->default('$');
      $table->dateTime('completed_at')->nullable();
      $table->timestamps();
    });

    Schema::table('works', function (Blueprint $table) {
      $table->foreign('invitation_id')->references('id')->on('invitations')->onDelete('cascade');
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
