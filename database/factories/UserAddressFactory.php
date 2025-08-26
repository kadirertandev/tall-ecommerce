<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAddress>
 */
class UserAddressFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'title' => $this->faker->word,
      'user_id' => User::factory(),
      'city' => $this->faker->word() . Str::random(5),
      'district' => $this->faker->word() . Str::random(5),
      'neighborhood' => $this->faker->word() . Str::random(5),
      'address_line' => $this->faker->word() . Str::random(5),
      'is_default' => false
    ];
  }
}
