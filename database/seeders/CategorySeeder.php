<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Category::create([
      "name" => "Bilgisayar",
      "slug" => "bilgisayar",
      "image" => "category_images/bilgisayar.png",
      "is_popular" => 1
    ]);
    Category::create([
      "name" => "Telefon",
      "slug" => "telefon",
      "image" => "category_images/telefon.png",
      "is_popular" => 1
    ]);
    Category::create([
      "name" => "Televizyon",
      "slug" => "televizyon",
      "image" => "category_images/televizyon.png",
      "is_popular" => 1
    ]);
    Category::create([
      "name" => "Klavye",
      "slug" => "klavye",
      "image" => "category_images/klavye.png",
      "is_popular" => 1
    ]);
    Category::create([
      "name" => "Mouse",
      "slug" => "mouse",
      "image" => "category_images/mouse.png",
      "is_popular" => 1
    ]);
    Category::create([
      "name" => "Küçük Ev Aletleri",
      "slug" => "kucuk-ev-aletleri",
      "image" => "category_images/kucuk-ev-aletleri.png",
      "is_popular" => 1
    ]);
  }
}
