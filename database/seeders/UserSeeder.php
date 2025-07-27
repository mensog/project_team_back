<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Adminov',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
            'group' => 'Admin',
            'avatar' => 'https://via.placeholder.com/100',
            'email_verified_at' => now(),
        ]);

        User::factory()->count(49)->create();
    }
}
