<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\LanguageTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            LanguageTableSeeder::class,
            PropertyStatusTableSeeder::class,
            CharacteristicsTableSeeder::class,
            HouseRuleTableSeeder::class,
            RoomTypeTableSeeder::class,
            BedTypeTableSeeder::class,
            BathroomItemTableSeeder::class,
            PaymentStatusTableSeeder::class,
            GeneralSettingsTableSeeder::class,
            RatingParameterTableSeeder::class,
            FPCTableSeeder::class,
            AdminTableSeeder::class,
            WalletFundingStatusTableSeeder::class,
            PartnerCommissionStatusTableSeeder::class,
        ]);
    }
}
