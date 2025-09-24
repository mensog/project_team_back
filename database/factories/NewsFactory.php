<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    protected $model = News::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'content' => $this->faker->paragraphs(3, true),
            'status' => $this->faker->randomElement(['active', 'completed']),
            'date' => $this->faker->dateTimeThisYear(),
            'preview_image' => $this->generatePreviewImage(),
        ];
    }

    private function generatePreviewImage(): string
    {
        $label = sprintf('News %s', Str::headline($this->faker->unique()->word()));

        return sprintf('https://dummyimage.com/300x200/f97316/ffffff&text=%s', urlencode($label));
    }
}
