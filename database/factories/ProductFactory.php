<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $name = $this->faker->words(3, true) . Str::random(5);

    return [
      'name' => $name,
      'slug' => Str::slug($name),
      'description' => $this->faker->paragraph(),
      'price' => $this->faker->randomFloat(2, 1000, 100000),
      'discount_amount' => $this->faker->randomElement([0, 500, 1000, 1200]),
      'image' => 'product_images/' . Str::random(5) . '.png',
      'category_id' => Category::factory(),
      'brand_id' => Brand::factory(),
    ];
  }
}
