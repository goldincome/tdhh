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
        Schema::create('booking_fee', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignUuid('booking_id');
            $table->decimal('booking_amount',15,2)->nullable();
            $table->decimal('cleaning_fee')->nullable();
            $table->decimal('service_fee')->nullable();
            $table->decimal('discount_amount')->nullable();
            $table->decimal('vat')->nullable();
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
        Schema::dropIfExists('booking_fee');
    }
};
