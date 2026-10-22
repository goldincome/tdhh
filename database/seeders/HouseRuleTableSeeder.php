<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property\HouseRule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HouseRuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $houseRules =  [
            ['name' =>	'Smoking not allowed'],
            ['name' =>	'Children not allowed'],
            ['name' =>	'Party not allowed'],
            ['name' =>	'Pets not allowed'],
        ];

        foreach ($houseRules as $rule){

            HouseRule::firstOrCreate(
                [
                    'name' => $rule['name']
                ],
                $rule);
        }
    }
}
