<?php

namespace Database\Factories;

use App\Models\Shared\Pengumuman;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shared\Pengumuman>
 */
class PengumumanFactory extends Factory
{
    protected $model = Pengumuman::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => fake()->sentence(),
            'isi' => fake()->paragraphs(3, true),
            'jenis' => fake()->randomElement(['pengumuman', 'artikel_berita']),
            'penulis_id' => User::factory(),
            'is_published' => true,
            'published_at' => now(),
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Indicate that the pengumuman is a news article.
     */
    public function news(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis' => 'artikel_berita',
        ]);
    }

    /**
     * Indicate that the pengumuman is an announcement.
     */
    public function announcement(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis' => 'pengumuman',
        ]);
    }

    /**
     * Indicate that the pengumuman is unpublished.
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
