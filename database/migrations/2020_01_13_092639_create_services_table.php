<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('services', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->bigInteger('user_id')->unsigned();
        $table->bigInteger('category_id')->unsigned();
        $table->string('title', 15);
        $table->enum('type', ['seek', 'provide'])->default('provide');
        $table->longText('description');
        $table->enum('payment_type', ['fixed', 'hourly', 'flexible'])->default('fixed');
        $table->boolean('negotiable')->default(false);
        $table->decimal('amount', 9,3)->nullable();
        $table->longText('terms')->nullable();
        $table->softDeletes();
        $table->timestamps();
      });

      Schema::table('services', function (Blueprint $table) {
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('services');
    }
}
