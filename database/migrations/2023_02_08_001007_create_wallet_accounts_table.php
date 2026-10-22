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
        Schema::create('wallet_accounts', function (Blueprint $table) {
            $table->uuid('id');
            $table->increments('reference')->startingValue(10000);
            $table->foreignUuid('user_id');
            $table->decimal('credit',15,2)->default(0)->nullable();
            $table->decimal('debit',15,2)->default(0)->nullable();
            $table->decimal('balance')->default(0)->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('wallet_accounts');
    }
};
