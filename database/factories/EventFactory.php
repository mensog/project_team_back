<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Project;
use Illuminate\Support\Str;
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
            'preview_image' => $this->generatePreviewImage(),
            'status' => $this->faker->randomElement(['active', 'completed']),
            'description' => $this->faker->paragraph,
            'start_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'project_id' => Project::inRandomOrder()->first()->id ?? Project::factory(),
        ];
    }

    private function generatePreviewImage(): string
    {
        $label = sprintf('Event %s', Str::headline($this->faker->unique()->word()));

        return sprintf('https://dummyimage.com/300x200/0a8dff/ffffff&text=%s', urlencode($label));
    }
}
