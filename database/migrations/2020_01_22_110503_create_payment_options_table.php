<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();//AUTH_blahblah
            $table->string('authorization_code')->index();//AUTH_blahblah
            $table->string("bin")->nullable();// "412345",
            $table->string("last4")->nullable();// "6789",
            $table->string("exp_month")->nullable();// "10",
            $table->string("exp_year")->nullable();// "2345",
            $table->string("channel")->nullable();// "card",
            $table->string("card_type")->nullable();// "mastercard debit",
            $table->string("bank")->nullable();// "Some Bank",
            $table->string("country_code")->nullable();// "NG",
            $table->string("brand")->nullable();// "mastercard",
            $table->string("reusable")->nullable();// true,
            $table->string("signature")->nullable();// "SIG_IJOJidkpd0293undjd"
            $table->timestamps();
        });

        Schema::table('payment_options', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_options');
    }
}
