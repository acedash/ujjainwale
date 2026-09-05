<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => User::factory(),
            'title' => fake()->sentence(),
            'body' => fake()->paragraphs(5, true),
            'published_at' => fake()->boolean(80) ? fake()->dateTimeBetween('-1 year', 'now') : null,
        ];
    }
}
