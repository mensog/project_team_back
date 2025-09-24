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
        $filename = $this->faker->image('storage/app/public/event_previews', 300, 200, null, false);

        return 'event_previews/' . $filename;
    }
}
