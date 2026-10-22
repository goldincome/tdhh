<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = [
            [
                'name' => 'TDHH Admin',
                'email' => 'admin@td.com',
                'password' => bcrypt('td@password'),
                'user_type' => Admin::SUPER_ADMIN,
                'email_verified_at' => now()
            ],

        ];

        foreach ($admins as  $admin) {
            Admin::firstOrCreate([
                'email' => $admin['email'],
            ], $admin);
        }
    }
}
