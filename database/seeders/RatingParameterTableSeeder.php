<?php

namespace Database\Seeders;

use App\Models\RatingParameter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingParameterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parameters = [
            ['name' => 'Communication with owner'],
            ['name' => 'Accuracy'],
            ['name' => 'Cleanliness'],
            ['name' => 'Location'],
            ['name' => 'Security'],
            ['name' => 'Value for Money'],
        ];

        foreach ($parameters as $parameter) {
            RatingParameter::firstOrCreate(
                [
                    'name' => $parameter['name'],
                ],
                $parameter
            );
        }
    }
}
