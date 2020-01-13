<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('service_id')->unsigned();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->mediumText('comment')->nullable();
            // $table->bigInteger('other_user_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('invitations', function (Blueprint $table) {
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('invitations');
    }
}