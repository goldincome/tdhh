<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->uuid('id');
            $table->increments('reference')->startingValue(10000);
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('neighborhood')->nullable();
            $table->decimal('price',15,2)->nullable();
            $table->text('price_meta')->nullable();
            $table->text('address')->nullable();
            $table->integer('bedroom_qty')->nullable();
            $table->integer('bathroom_qty')->nullable();
            $table->integer('country_id');
            $table->integer('min_bookable_night')->nullable();
            $table->integer('state_id')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->uuid('listed_by');
            $table->string('map_address')->nullable();
            $table->boolean('status_id')->default(1);
            $table->decimal('cleaning_fee')->nullable();
            $table->time('check_in_from')->nullable();
            $table->time('check_in_to')->nullable();
            $table->time('check_out_from')->nullable();
            $table->time('check_out_to')->nullable();
            $table->string('parking_is_available')->default('no');
            $table->text('parking_options')->nullable();
            $table->integer('room_type_id')->nullable();
            $table->string('property_type')->nullable();
            $table->string('bed_types')->nullable();
            $table->integer('room_quantity')->nullable();
            $table->integer('max_guest')->nullable();
            $table->string('room_size')->nullable();
            $table->string('bathroom_type')->nullable();
            $table->integer('living_room_qty')->nullable();
            $table->text('refund_policy')->nullable();
            $table->boolean('sofa_availability')->nullable();
            $table->integer('sofa_qty')->nullable();

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
        Schema::dropIfExists('properties');
    }
}
