<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();

        User::create([
            'name' => 'user',
            'first_name' => 'Admin',
            'last_name' => 'Adminov',
            'email' => 'admin@example.com',
            'avatar' => 'https://via.placeholder.com/100',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);
    }
}
