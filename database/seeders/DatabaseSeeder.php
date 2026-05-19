<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@tifico.com',
            'password' => bcrypt('1'),
            'orgn_code' => 'IT',
            'gander' => 'male',
            'phone' => '1234567890',
            'designation' => 'admin',
            'status' => true,
        ]);
    }
}
