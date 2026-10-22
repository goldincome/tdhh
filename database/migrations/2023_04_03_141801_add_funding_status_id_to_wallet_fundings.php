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
            $table->tinyInteger('wallet_funding_status_id')->after('final_approval');
            $table->text('admin_actions')->nullable()->after('final_approval');
            $table->text('rejection_reasons')->change();

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
            $table->dropColumn('final_approval');
            $table->dropColumn('wallet_funding_status_id');
        });
    }
};
