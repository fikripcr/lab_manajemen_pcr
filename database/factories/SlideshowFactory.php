<?php

namespace Database\Factories;

use App\Models\Shared\Slideshow;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shared\Slideshow>
 */
class SlideshowFactory extends Factory
{
    protected $model = Slideshow::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image_url' => 'slideshows/default-' . fake()->numberBetween(1, 5) . '.jpg',
            'title' => fake()->sentence(4),
            'caption' => fake()->sentence(8),
            'link' => fake()->optional(0.7)->url(),
            'seq' => fake()->numberBetween(1, 10),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the slideshow is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
