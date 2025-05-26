<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
            User::factory()->create(
                [
                    'name' => 'Nafis',
                    'email' => 'azkaalfarisi04@gmail.com',
                    'password' => bcrypt('icikiwir'),
                ]
            );
            User::factory()->create(
                [
                    'name' => 'Azka',
                    'email' => 'azkanafis04@gmail.com',
                    'password' => bcrypt('icikiwir'),
                ]
            );
    }
}
