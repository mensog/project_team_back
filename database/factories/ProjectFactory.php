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
            'preview_image' => $this->faker->imageUrl(300, 200, 'business'),
            'certificate' => $this->faker->optional()->filePath(false, 'certificates'),
            'status' => $this->faker->randomElement(['active', 'completed']),
            'user_id' => User::factory(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Project $project) {
            $users = collect([1])->merge(User::factory()->count(1)->create()->pluck('id'));
            $project->participants()->attach($users);
        });
    }
}
