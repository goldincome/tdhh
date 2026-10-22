<?php

namespace Database\Seeders;

use App\Models\Property\Language;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Log;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages =  [
            ['name' =>	'English'],
            ['name' =>	'Spanish'],
            ['name' =>	'French'],
            ['name' =>	'German'],
            ['name' =>	'Arabic'],
            ['name' =>	'Yoruba'],
            ['name' =>	'Italian'],
            ['name' =>	'Chinese'],
            ['name' =>	'Japanese'],
            ['name' =>	'Portuguese'],
            ['name' =>	'Yoruba'],
            ['name' =>	'Igbo'],
            ['name' =>	'Hausa'],
            ['name' =>	'Indian'],
            ['name' =>	'Others'],
        ];

        foreach ($languages as $language){

            Language::firstOrCreate(
                [
                    'name' => $language['name']
                ],
                $language);
        }
    }
}
