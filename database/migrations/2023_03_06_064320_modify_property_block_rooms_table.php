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
        Schema::table('property_block_rooms', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date','affected_days']);
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
        Schema::table('property_block_rooms', function (Blueprint $table) {
            $table->dropColumn(['date']);
            $table->timestamp('end_date')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->text('affected_days')->nullable();
        });
    }
};
