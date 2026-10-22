<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GeneralSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'id' => GeneralSetting::PARTNER_COMMISSION,
                'name' => 'partner_commission_percent',
                'value' => 5,
                'description' => 'Percent Commission Percent',

            ],
        ];

        foreach ($settings as $setting) {
            GeneralSetting::firstOrCreate(
                [
                    'id' => $setting['id'],
                ],
                $setting
            );
        }
    }
}
