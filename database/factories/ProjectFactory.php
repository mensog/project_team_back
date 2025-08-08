<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'description' => $this->faker->paragraphs(1, true),
            'preview_image' => $this->faker->imageUrl(300, 200, 'business'),
            'certificate' => $this->faker->optional()->filePath(false, 'certificates'),
            'status' => $this->faker->randomElement(['active', 'completed']),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'start_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'is_approved' => $this->faker->boolean(80),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Project $project) {
            $users = User::inRandomOrder()->take(5)->pluck('id');
            $project->participants()->attach($users);
        });
    }
}
