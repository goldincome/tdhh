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
        Schema::table('wallet_fundings', function (Blueprint $table) {
            $table->decimal('converted_amount',15,2)->nullable()->after('currency_code');
            $table->string('converted_currency_code')->nullable()->after('converted_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_fundings', function (Blueprint $table) {
           $table->dropColumn(['converted_amount', 'converted_currency_code']);
        });
    }
};
