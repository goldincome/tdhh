<?php

use App\Models\PartnerCommission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partner_commissions', function (Blueprint $table) {
            $table->dropUnique(['reservation_id']); 
            $table->tinyInteger('commission_type')->default(PartnerCommission::FROM_BOOKING)->after('commission_status_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partner_commissions', function (Blueprint $table) {
            $table->foreignUuid('reservation_id')->unique()->change();
            $table->dropColumn('commission_type');
        });
    }
};
