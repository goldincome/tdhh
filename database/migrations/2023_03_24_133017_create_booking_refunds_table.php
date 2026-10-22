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
        Schema::create('booking_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('reservation_id');
            $table->foreignUuid('user_id');
            $table->decimal('refund_amount',15,2)->nullable();
            $table->decimal('cancellation_fee',15,2)->nullable();
            $table->decimal('percentage_charged')->nullable();
            $table->text('reason_message')->nullable();
            $table->string('requested_by')->nullable();
            $table->string('approved_by')->nullable();
            $table->tinyInteger('refund_status')->nullable();
            $table->tinyInteger('is_refund_approved')->nullable()->default(false);
            $table->tinyInteger('is_refunded')->nullable()->default(false);
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
        Schema::dropIfExists('booking_refunds');
    }
};
