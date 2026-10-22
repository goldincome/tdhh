<?php

namespace Database\Seeders;

use App\Models\Property\BathroomItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BathroomItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bathroomItem =  [
            ['name' =>	'Toilet paper',],
            ['name' =>	'Toilet'],
            ['name' =>	'Free toiletries'],
            ['name' =>	'Shower'],
            ['name' =>	'Hand dryer'],
            ['name' =>	'Bathrobe'],
            ['name' =>	'Towel'],
            ['name' =>	'Toothpaste'],
            ['name' =>	'Gel'],
        ];

        foreach ($bathroomItem as $item){
             BathroomItem::firstOrCreate(
                [
                    'name' => $item['name']
                ],
                $item);
        }
    }
}
