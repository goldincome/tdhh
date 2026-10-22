<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentStatus;

class PaymentStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses =  [
            [ 'id' => PaymentStatus::PAID, 'name' => 'Paid', 'color' => 'green'],
            [ 'id' => PaymentStatus::CANCELLED_BY_CUSTOMER, 'name' => 'Cancelled By You', 'color' => 'red'],
            [ 'id' => PaymentStatus::CANCELLED_BY_PARTNER, 'name' => 'Cancelled By Partner', 'color' => 'red'],
            [ 'id' => PaymentStatus::CANCELLED_BY_ADMIN, 'name' => 'Cancelled By Admin', 'color' => 'red'],
            [ 'id' => PaymentStatus::REFUND_REQUESTED, 'name' => 'Refund Requested', 'color' => 'orange'],
            [ 'id' => PaymentStatus::REFUNDED, 'name' => 'Refunded', 'color' => 'green'],
        ];

        foreach ($statuses as $status){
            PaymentStatus::firstOrCreate(
                [
                    'id' => $status['id']
                ],
                $status);
        }
    }
}
