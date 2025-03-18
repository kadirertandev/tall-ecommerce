<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
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
    Product::create([
      "name" => "Gigabyte Aorus 15",
      "slug" => $this->generate_slug("Gigabyte Aorus 15"),
      "description" => "i7-13700H 16GB 1TB SSD 8GB RTX 4060 15.6″ QHD 165Hz Win11 Home Gaming (Oyuncu) Notebook",
      "price" => 64349.00,
      "discount_amount" => 1200,
      "image" => "product_images/gigabyte.webp",
      "category_id" => 1,
      "brand_id" => 1
    ]);
    Product::create([
      "name" => "Asus VivoBook X1504VA-NJ104",
      "slug" => $this->generate_slug("Asus VivoBook X1504VA-NJ104"),
      "description" => "i5-1335U 8 GB 512 GB SSD Iris Xe Graphics 15.6″ Full HD FreeDos Notebook",
      "price" => 16899.00,
      "discount_amount" => 500,
      "image" => "product_images/asus.png",
      "category_id" => 1,
      "brand_id" => 2,
    ]);
    Product::create([
      "name" => "ASUS Rog Strıx G15 G513rc-hn193",
      "slug" => $this->generate_slug("ASUS Rog Strıx G15 G513rc-hn193"),
      "description" => "Amd Ryzen 7-6800h 16gb Ddr5 512gb Ssd Rtx3050 15.6\" Fhd 144hz Dos",
      "price" => 31899,
      "discount_amount" => 700,
      "image" => "product_images/asus2.png",
      "category_id" => 1,
      "brand_id" => 2,
    ]);
    Product::create([
      "name" => "Dell G16 7630",
      "slug" => $this->generate_slug("Dell G16 7630"),
      "description" => "8GB RTX4070 i9-13900HX 16GB1TB SSD 16 inç QHD+ 165Hz G76302401020U",
      "price" => 59999,
      "discount_amount" => 700,
      "image" => "product_images/dell.png",
      "category_id" => 1,
      "brand_id" => 11,
    ]);
    Product::create([
      "name" => "Dell Inspiron 3520",
      "slug" => $this->generate_slug("Dell Inspiron 3520"),
      "description" => "Intel Core i5-1235U 16GB 512GB SSD 15.6\" FHD Linux Siyah Dizüstü Bilgisayar",
      "price" => 14999,
      "discount_amount" => 700,
      "image" => "product_images/dell2.png",
      "category_id" => 1,
      "brand_id" => 11,
    ]);
    Product::create([
      "name" => "MSI Nb Thın Gf63 12ucx-427xtr",
      "slug" => $this->generate_slug("MSI Nb Thın Gf63 12ucx-427xtr"),
      "description" => "I5-12450h 8gb Ddr4 Rtx2050 Gddr6 4gb 512gb Ssd 15.6 Fhd 144hz Dos",
      "price" => 22148,
      "discount_amount" => 700,
      "image" => "product_images/msi.png",
      "category_id" => 1,
      "brand_id" => 12,
    ]);
    Product::create([
      "name" => "MSI KATANA GF66 11UE-1019XTR",
      "slug" => $this->generate_slug("MSI KATANA GF66 11UE-1019XTR"),
      "description" => "I5-11400H 16GB DDR4 RTX3060 GDDR6 6GB 512GB SSD 15.6 FHD 144Hz DOS",
      "price" => 33499,
      "image" => "product_images/msi2.png",
      "category_id" => 1,
      "brand_id" => 12,
    ]);
    Product::create([
      "name" => "MSI CYBORG 15 A12VF-675XTR",
      "slug" => $this->generate_slug("MSI CYBORG 15 A12VF-675XTR"),
      "description" => "i7 12650H 16GB 512GB SSD RTX4060 Freedos 15.6\" FHD 144Hz Bilgisayar",
      "price" => 41965,
      "image" => "product_images/msi3.png",
      "category_id" => 1,
      "brand_id" => 12,
    ]);
    Product::create([
      "name" => "HP Victus Gaming 15-FA0011NT 80D33EA",
      "slug" => $this->generate_slug("HP Victus Gaming 15-FA0011NT 80D33EA"),
      "description" => "i5 12450H 16GB 512GB SSD RTX3050 Freedos 15.6\" FHD 144Hz",
      "price" => 25879,
      "image" => "product_images/hp.png",
      "category_id" => 1,
      "brand_id" => 13,
    ]);
    Product::create([
      "name" => "HP Pavilion 15-EH3011NT",
      "slug" => $this->generate_slug("HP Pavilion 15-EH3011NT"),
      "description" => "AMD Ryzen 5-7530U 8GB 512GB DOS 15.6\" FHD Gümüş Laptop (HP Türkiye Garantili)",
      "price" => 13999,
      "image" => "product_images/hp2.png",
      "category_id" => 1,
      "brand_id" => 13,
    ]);
    Product::create([
      "name" => "Casper Excalibur G770.1245-DFJ0X-B",
      "slug" => $this->generate_slug("Casper Excalibur G770.1245-DFJ0X-B"),
      "description" => "Intel Core i5-12450H 32GB RAM 1TB NVME SSD GEN4 4GB RTX3050 Freedos",
      "price" => 31199,
      "image" => "product_images/casper.png",
      "category_id" => 1,
      "brand_id" => 14,
    ]);
  }
}
