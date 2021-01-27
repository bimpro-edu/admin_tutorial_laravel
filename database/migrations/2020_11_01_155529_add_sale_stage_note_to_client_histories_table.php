<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSaleStageNoteToClientHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_histories', function (Blueprint $table) {
            $table->string('sale_stage')->nullable(true);
            $table->text('note')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_histories', function (Blueprint $table) {
            $table->dropColumn('sale_stage');
            $table->dropColumn('note');
        });
    }
}
