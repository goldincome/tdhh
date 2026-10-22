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
        Schema::create('reservations', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignUuid('property_id');
            $table->foreignUuid('user_id');
            $table->date('proposed_check_in_date')->nullable();
            $table->time('proposed_check_in_time')->nullable();
            $table->date('proposed_check_out_date')->nullable();
            $table->time('proposed_check_out_time')->nullable();
            $table->date('actual_check_in_date')->nullable();
            $table->time('actual_check_in_time')->nullable();
            $table->date('actual_check_out_date')->nullable();
            $table->time('actual_check_out_time')->nullable();
            $table->string('adult')->nullable();
            $table->string('children')->nullable();
            $table->string('pet')->nullable();
            $table->text('message')->nullable();
            $table->integer('booking_status_id');
            $table->integer('payment_status_id');
            $table->timestamp('payment_date')->nullable();
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
        Schema::dropIfExists('reservations');
    }
};
