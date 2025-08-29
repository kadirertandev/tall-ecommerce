<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailyDealProduct>
 */
class DailyDealProductFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $product = Product::factory()->create();

    return [
      "product_id" => $product->id,
      "start_date" => now(),
      "end_date" => now()->addHours(24),
      "discount_amount" => ($product->price * $this->faker->randomElement([10, 20, 30])) / 100
    ];
  }
}
