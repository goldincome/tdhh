<?php

namespace Database\Seeders;

use App\Models\FrontPageCity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FPCTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            ['state_id' => 3866, 'status' => 1],
            ['state_id' => 3868, 'status' => 1],
            ['state_id' => 3825, 'status' => 1],
            ['state_id' => 3829, 'status' => 1],
            ['state_id' => 3855, 'status' => 1],
            ['state_id' => 3898, 'status' => 1],
            ['state_id' => 3899, 'status' => 1],
            ['state_id' => 3900, 'status' => 1],

        ];

        foreach ($cities as $city) {
            FrontPageCity::firstOrCreate(
                [
                    'state_id' => $city['state_id'],
                ],
                $city
            );
        }
    }
}
