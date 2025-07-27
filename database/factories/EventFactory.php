<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'preview_image' => $this->faker->imageUrl(300, 200, 'events'),
            'date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'status' => $this->faker->randomElement(['active', 'completed']),
            'description' => $this->faker->paragraph,
            'project_id' => Project::inRandomOrder()->first()->id ?? Project::factory(),
        ];
    }
}
