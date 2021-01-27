<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('national_id', 30)->nullable(true);
            $table->string('phone', 20)->nullable(true);
            $table->string('address');
            $table->decimal('disbursement_amount', 15, 2)->nullable(true);
            $table->bigInteger('assign_user_id')->nullable(true);
            $table->bigInteger('campaign_id')->nullable(true);
            $table->date('import_date')->nullable(true);
            $table->bigInteger('sale_stage_id');
            $table->decimal('money_limit', 15, 2)->nullable(true);
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
        Schema::dropIfExists('clients');
    }
}
