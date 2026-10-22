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
            //parking_options, property_facilities, bathroom_items
            $table->renameColumn('parking_options','guest_info');
            $table->renameColumn('property_facilities','location_details');
            $table->renameColumn('bathroom_items', 'price_details');
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
            $table->renameColumn('guest_info', 'parking_options');
            $table->renameColumn('location_details', 'property_facilities');
            $table->renameColumn('price_details', 'bathroom_items');
        });
    }
};
