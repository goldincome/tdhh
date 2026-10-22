<?php

namespace Database\Seeders;

use App\Models\PartnerCommissionStatus;
use Illuminate\Database\Seeder;


class PartnerCommissionStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses =  [
            [ 'id' => PartnerCommissionStatus::PAID, 'name' => 'Paid', 'color' => 'green'],
            [ 'id' => PartnerCommissionStatus::IN_PROGRESS, 'name' => 'Reserved', 'color' => 'orange'],
            [ 'id' => PartnerCommissionStatus::CANCELLED, 'name' => 'Cancelled', 'color' => 'red'],

        ];

        foreach ($statuses as $status){
            PartnerCommissionStatus::firstOrCreate(
                [
                    'id' => $status['id']
                ],
                $status);
        }
    }
}
