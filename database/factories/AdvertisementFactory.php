<?php

namespace Database\Factories;

use App\Models\Advertisement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Advertisement>
 */
class AdvertisementFactory extends Factory
{
    protected $model = Advertisement::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->company(),
            'placement' => fake()->randomElement(['billboard', 'sponsor_card', 'in_feed', 'left_rail', 'right_rail']),
            'subtitle' => fake()->catchPhrase(),
            'badge_text' => 'Sponsored',
            'description' => fake()->sentence(),
            'cta_text' => 'Learn More',
            'cta_url' => fake()->url(),
            'phone' => fake()->phoneNumber(),
            'icon' => '📢',
            'is_active' => true,
            'order' => fake()->numberBetween(0, 10),
        ];
    }
}
