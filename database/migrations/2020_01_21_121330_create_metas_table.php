<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metas', function (Blueprint $table) {
            $table->bigIncrements('id');
<<<<<<< HEAD:database/migrations/2020_01_13_092736_create_user_metas_table.php
            $table->integer('user_id')->unsigned();
            $table->string('name');
=======
            $table->morphs('metable');
            $table->string('name', 30);
>>>>>>> c38a5bc02d349eb2b26d3fd4b713d68792e1d286:database/migrations/2020_01_21_121330_create_metas_table.php
            $table->string('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metas');
    }
}
