<?php

namespace Database\Seeders;

use App\Models\Property\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roomTypes =  [
            ['name' =>	'Shared - A room in a shared apartment'],
            ['name' =>	'Single - Standalone room'],
            // ['name' =>	'Twin'],
            // ['name' =>	'Twin/Double'],
            // ['name' =>	'Triple'],
            // ['name' =>	'Quadruple'],
            // ['name' =>	'Family'],
        ];

        foreach ($roomTypes as $type){
            RoomType::firstOrCreate(
                [
                    'name' => $type['name']
                ],
                $type);
        }
    }
}
