<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WalletFundingStatus;

class WalletFundingStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses =  [
            [ 'id' => WalletFundingStatus::APPROVED, 'name' => 'Approved', 'color' => 'green'],
            [ 'id' => WalletFundingStatus::SUBMITTED, 'name' => 'Submitted', 'color' => 'blue'],
            [ 'id' => WalletFundingStatus::PROCESSING, 'name' => 'Processing', 'color' => 'orange'],
            [ 'id' => WalletFundingStatus::REJECTED, 'name' => 'Rejected', 'color' => 'red'],
            [ 'id' => WalletFundingStatus::CANCELLED, 'name' => 'Cancelled', 'color' => 'red'],
            [ 'id' => WalletFundingStatus::REFUND_REQUESTED, 'name' => 'Refund Requested', 'color' => 'blue'],
            [ 'id' => WalletFundingStatus::REFUNDED, 'name' => 'Refund Approved', 'color' => 'green'],
        ];

        foreach ($statuses as $status){
            WalletFundingStatus::firstOrCreate(
                [
                    'id' => $status['id']
                ],
                $status);
        }
    }
}
