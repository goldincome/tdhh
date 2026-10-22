<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property\PropertyStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PropertyStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses =  [
            [ 'id' => PropertyStatus::PENDING, 'name' => 'Pending'],
            [ 'id' => PropertyStatus::AVAILABLE, 'name' => 'Available'],
            [ 'id' => PropertyStatus::SUSPENDED_BY_PARTNER, 'name' => 'Suspended By Partner'],
            [ 'id' => PropertyStatus::SUSPENDED_BY_ADMIN, 'name' => 'Suspended By Admin'],
            [ 'id' => PropertyStatus::DISABLED, 'name' => 'Disabled'],

        ];

        foreach ($statuses as $status){
            PropertyStatus::firstOrCreate(
                [
                    'id' => $status['id']
                ],
                $status);
        }
    }
}
