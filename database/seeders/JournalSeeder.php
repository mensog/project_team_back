<?php

namespace Database\Seeders;

use App\Models\Journal;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->info('Пользователи не найдены, пропуск JournalSeeder.');
            return;
        }

        foreach ($users->take(5) as $user) {
            $journal = Journal::factory()->create();
            $journal->participants()->attach($user->id);
        }
    }
}
