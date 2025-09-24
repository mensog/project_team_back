<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $filename = $this->faker->image('storage/app/public/news_previews', 300, 200, null, false);

        return 'news_previews/' . $filename;
    }
}
