<?php

namespace Database\Factories;

use App\Models\Journal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Journal>
 */
class JournalFactory extends Factory
{
    protected $model = Journal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'title' => $this->faker->sentence(3),
            'type' => $this->faker->randomElement(['event', 'meeting']),
            'date' => $this->faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Journal $journal) {
            $allUsers = User::pluck('id');
            $syncData = $allUsers->mapWithKeys(function ($userId) {
                return [$userId => ['status' => $this->faker->randomElement(['present', 'absent'])]];
            })->toArray();

            $journal->participants()->sync($syncData);
        });
    }
}
