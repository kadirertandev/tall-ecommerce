<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
  public function generate_slug(string $name)
  {
    return Str::slug($name);
  }

  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $brands =
      [
        [
          "name" => "Gigabyte",
          "slug" => $this->generate_slug("Gigabyte"),
          "image" => "brand_images/gigabyte.png",
          'created_at' => now(),
          'updated_at' => now(),
        ],
        [
          "name" => "Asus",
          "slug" => $this->generate_slug("Asus"),
          "image" => "brand_images/asus.png",
          'created_at' => now(),
          'updated_at' => now(),
        ],
        [
          "name" => "Samsung",
          "slug" => $this->generate_slug("Samsung"),
          "image" => "brand_images/samsung.png",
          'created_at' => now(),
          'updated_at' => now(),
        ],
        [
          "name" => "Toshiba",
          "slug" => $this->generate_slug("Toshiba"),
          "image" => "brand_images/toshiba.png",
          'created_at' => now(),
          'updated_at' => now(),
        ],
        [
          "name" => "Lenovo",
          "slug" => $this->generate_slug("Lenovo"),
          "image" => "brand_images/lenovo.png",
          'created_at' => now(),
          'updated_at' => now(),
        ],
        [
          "name" => "Logitech",
          "slug" => $this->generate_slug("Logitech"),
          "image" => "brand_images/logitech.png",
          'created_at' => now(),
          'updated_at' => now(),
        ],
        [
          "name" => "Roborock",
          "slug" => $this->generate_slug("Roborock"),
          "image" => "brand_images/roborock.png",
          'created_at' => now(),
          'updated_at' => now(),
        ],
        [
          "name" => "Apple",
          "slug" => $this->generate_slug("Apple"),
          "image" => "brand_images/apple.png",
          'created_at' => now(),
          'updated_at' => now(),
        ],
        [
          "name" => "Xiaomi",
          "slug" => $this->generate_slug("Xiaomi"),
          "image" => "brand_images/xiaomi.png",
          'created_at' => now(),
          'updated_at' => now(),
        ],
        [
          "name" => "Philips",
          "slug" => $this->generate_slug("Philips"),
          "image" => "brand_images/philips.png",
          'created_at' => now(),
          'updated_at' => now(),
        ],
        [
          "name" => "Dell",
          "slug" => $this->generate_slug("Dell"),
          "image" => "brand_images/dell.png",
          'created_at' => now(),
          'updated_at' => now(),
        ],
        [
          "name" => "MSI",
          "slug" => $this->generate_slug("MSI"),
          "image" => "brand_images/msi.png",
          'created_at' => now(),
          'updated_at' => now(),
        ],
        [
          "name" => "HP",
          "slug" => $this->generate_slug("HP"),
          "image" => "brand_images/hp.png",
          'created_at' => now(),
          'updated_at' => now(),
        ],
        [
          "name" => "Casper",
          "slug" => $this->generate_slug("Casper"),
          "image" => "brand_images/casper.png",
          'created_at' => now(),
          'updated_at' => now(),
        ],
      ];
    Brand::insert($brands);
  }
}
