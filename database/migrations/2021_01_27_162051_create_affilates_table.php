<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffilatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affilates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fullname', 100)->nullable(true);
            $table->date('birthday')->nullable(true);
            $table->string('email')->nullable(true);
            $table->string('phone', 20)->nullable(true);
            $table->string('address_line')->nullable(true);
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
        Schema::dropIfExists('affilates');
    }
}
