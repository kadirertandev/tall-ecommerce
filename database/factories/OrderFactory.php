<?php

namespace Database\Factories;

use App\Enums\OrderStatusType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'user_id' => User::factory(),
      'city' => $this->faker->city(),
      'district' => $this->faker->streetName(),
      'neighborhood' => $this->faker->word(),
      'address_line' => $this->faker->address(),
      'status' => $this->faker->randomElement(OrderStatusType::cases())->value,
      'created_at' => now()->addDays($this->faker->numberBetween(1, 5)),
      'updated_at' => now(),
    ];
  }

  public function orderPlaced(): static
  {
    return $this->state(fn($state) => ["status" => "Sipariş Verildi"]);
  }

  public function preparing(): static
  {
    return $this->state(fn($state) => ["status" => "Hazırlanıyor"]);
  }

  public function shipped(): static
  {
    return $this->state(fn($state) => ["status" => "Kargoya Verildi"]);
  }

  public function delivered(): static
  {
    return $this->state(fn($state) => ["status" => "Teslim Edildi"]);
  }
}
