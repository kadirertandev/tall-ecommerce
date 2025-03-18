<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */

  public function run(): void
  {
    User::factory(10)->create();

    User::create([
      'first_name' => "kadir",
      'last_name' => "ertan",
      'email' => "kadir@test.com",
      'is_admin' => 0,
      'password' => bcrypt("asdfasdf")
    ]);

    $this->call([
      RolesPermissionsSeeder::class,
      CategorySeeder::class,
      BrandSeeder::class,
      CategoryBrandSeeder::class,
      ProductSeeder::class,
      DailyDealProductSeeder::class,
      WeeklyDealProductSeeder::class,
      ProductReviewSeeder::class,
    ]);
  }
}
