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
        Schema::create('partner_commissions', function (Blueprint $table) {
            $table->uuid('id');
            $table->increments('ref_no')->startingValue(100000);
            $table->foreignUuid('reservation_id')->unique();
            $table->foreignUuid('partner_id');
            $table->decimal('commission_amt',15,2);
            $table->decimal('payout_amt',15,2);
            $table->decimal('commission_percentage');
            $table->tinyInteger('commission_status_id');
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
        Schema::dropIfExists('partner_commissions');
    }
};
