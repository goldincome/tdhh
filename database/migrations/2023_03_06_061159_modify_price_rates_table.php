<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_rates', function (Blueprint $table) {
            $table->dropColumn(['end_date', 'affected_days', 'start_date']);
            $table->date('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_rates', function (Blueprint $table) {
            $table->dropColumn(['date']);
            $table->timestamp('end_date')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->string('affected_days')->nullable();
        });
    }
};
