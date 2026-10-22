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
            $table->boolean('is_refund')->default(false)->after('admin_actions');
            $table->text('options')->nullable()->after('is_refund');
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
            $table->dropColumn('is_refund');
            $table->dropColumn('options');
        });
    }
};
