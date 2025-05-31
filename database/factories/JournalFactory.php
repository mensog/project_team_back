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
            'action' => $this->faker->sentence,
            'date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['present', 'absent']),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Journal $journal) {
            $users = User::inRandomOrder()->take(rand(1, 5))->pluck('id');
            $journal->participants()->sync(
                $users->mapWithKeys(function ($userId) {
                    return [$userId => ['status' => $this->faker->randomElement(['present', 'absent'])]];
                })->toArray()
            );
        });
    }
}
