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
        Schema::create('wallet_fundings', function (Blueprint $table) {
            $table->uuid('id');
            $table->increments('reference')->startingValue(10000);
            $table->foreignUuid('user_id');
            $table->foreignId('bank_account_id');
            $table->string('bank_name')->nullable();
            $table->string('teller_image')->nullable();
            $table->string('account_number')->nullable();
            $table->string('depositor_name')->nullable();
            $table->decimal('amount_paid',15,2)->default(0)->nullable();
            $table->date('date_paid')->nullable();
            $table->string('currency_code')->nullable();
            $table->string('rejection_reasons')->nullable();
            $table->boolean('first_approval')->default(false);
            $table->boolean('final_approval')->default(false);
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
        Schema::dropIfExists('wallet_fundings');
    }
};
