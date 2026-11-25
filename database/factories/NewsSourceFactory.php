<?php

namespace Database\Factories;

use App\Models\NewsSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NewsSource>
 */
class NewsSourceFactory extends Factory
{
    protected $model = NewsSource::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'feed_url' => fake()->url(),
            'website_url' => fake()->url(),
            'active' => true,
        ];
    }
}
