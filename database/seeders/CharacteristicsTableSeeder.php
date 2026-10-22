<?php

namespace Database\Seeders;

use App\Models\Characteristic;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CharacteristicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $characteristics =  [
            ['name' =>	'Swimming pool'],
            ['name' =>	'Gas cooker'],
            ['name' =>	'Washing Machine'],
            ['name' =>	'Free Wifi'],
            ['name' =>	'Refrigerator'],
            ['name' =>	'Iron'],
            ['name' =>	'Heater'],
            ['name' =>	'Microwave'],
            ['name' =>	'Free breakfast'],
        ];

        foreach ($characteristics as $characteristic){

            Characteristic::firstOrCreate(
                [
                    'name' => $characteristic['name']
                ],
                $characteristic);
        }
    }
}
