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
        Schema::table('reservations', function (Blueprint $table) {
            $table->text('parking_details')->change();
            $table->text('property_facilities')->change();
            $table->text('property_rules')->change();
            $table->text('bathroom_items')->change();
            $table->text('property_characteristics')->change();
            $table->text('cleaning_options')->change();
            $table->text('parking_options')->change();
            $table->text('room_details')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            //
        });
    }
};
