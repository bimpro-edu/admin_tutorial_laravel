<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable(true);
            $table->integer('source_size')->nullable(true);
            $table->integer('small_size')->nullable(true);
            $table->integer('medium_size')->nullable(true);
            $table->integer('large_size')->nullable(true);
            $table->string('source_path')->nullable(true);
            $table->string('small_path')->nullable(true);
            $table->string('medium_path')->nullable(true);
            $table->string('large_path')->nullable(true);
            $table->bigInteger('foreign_hash');
            $table->tinyInteger('type')->nullable(true);
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
        Schema::dropIfExists('files');
    }
}
