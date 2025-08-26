<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $name = $this->faker->word() . Str::random(5);

    return [
      'name' => ucfirst($name),
      'slug' => Str::slug($name),
      'image' => 'category_images/' . Str::random(5) . '.png',
      'is_popular' => $this->faker->boolean(20),
    ];
  }
}
