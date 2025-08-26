<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $name = $this->faker->company() . Str::random(5);

    return [
      'name' => $name,
      'slug' => Str::slug($name),
      'image' => 'brand_images/' . Str::random(5) . '.png',
      'is_popular' => $this->faker->boolean(20),
      'created_at' => now(),
      'updated_at' => now(),
    ];
  }
}
