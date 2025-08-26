<?php

namespace Database\Factories;

use App\Enums\ReviewStatusType;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductReview>
 */
class ProductReviewFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'title' => $this->faker->sentence(4),
      'comment' => $this->faker->paragraph(3),
      'rating' => $this->faker->numberBetween(1, 5),
      'user_id' => User::factory(),
      'product_id' => Product::factory(),
      'status' => $this->faker->randomElement(ReviewStatusType::cases())->value,
      'updated_by' => null,
      'deleted_by' => null,
    ];
  }

  public function approved(): static
  {
    return $this->state(fn() => ['status' => 'approved']);
  }

  public function evaluating(): static
  {
    return $this->state(fn() => ['status' => 'evaluating']);
  }

  public function rejected(): static
  {
    return $this->state(fn() => ['status' => 'rejected']);
  }
}
