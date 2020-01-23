<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edits', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->morphs('edit');
          $table->morphs('editor');
          $table->nullableMorphs('moderator');
          $table->string('name', 100);
          $table->json('changes');
          $table->enum('status', ['pending', 'accepted', 'rejected', 'canceled'])->default('pending');
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
        Schema::dropIfExists('edits');
    }
}
