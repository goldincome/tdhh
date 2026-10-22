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
            $table->increments('reservation_no')->startingValue(100000)->after('id');
            $table->decimal('price')->default(0.00)->after('user_id');
            $table->integer('num_of_night')->default(1)->after('price');
            $table->decimal('cleaning_fee')->default(0.00)->after('num_of_night');
            $table->decimal('parking_fee')->default(0.00)->after('cleaning_fee');
            $table->decimal('tax')->default(0.00)->after('parking_fee');
            $table->decimal('subtotal',15,2)->default(0.00)->after('tax');
            $table->decimal('total',15,2)->default(0.00)->after('subtotal');
            $table->integer('num_of_guest')->nullable()->after('total');
            $table->integer('max_num_of_guest')->nullable()->after('num_of_guest');
            $table->string('parking_details')->nullable()->after('max_num_of_guest');
            $table->string('property_facilities')->nullable()->after('parking_details');
            $table->string('property_rules')->nullable()->after('property_facilities');
            $table->string('bathroom_items')->nullable()->after('property_rules');
            $table->string('property_characteristics')->nullable()->after('bathroom_items');
            $table->string('cleaning_options')->nullable()->after('property_characteristics');
            $table->string('parking_options')->nullable()->after('cleaning_options');
            $table->string('room_details')->nullable()->after('parking_options');
            $table->string('payment_method')->nullable()->after('room_details');
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
            $table->dropColumn(['price','num_of_night', 'reservation_no','cleaning_fee','parking_fee',
            'tax','subtotal','total','payment_method', 'num_of_guest', 'max_num_of_guest', 'parking_details',
            'property_facilities','property_rules','bathroom_items','property_characteristics','cleaning_options',
            'parking_options','room_details'
        ]);

        });
    }
};
