<?php

namespace Database\Seeders;

use App\Models\Property\BedType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BedTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bedTypes =  [
            ['name' =>	'Single bed',],
            ['name' =>	'Double bed'],
            ['name' =>	'Large bed'],
            ['name' =>	'Extra large'],
        ];

        foreach ($bedTypes as $type){
             BedType::firstOrCreate(
                [
                    'name' => $type['name']
                ],
                $type);
        }
    }
}
