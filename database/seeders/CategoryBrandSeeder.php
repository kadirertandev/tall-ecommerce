<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryBrandSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('category_brands')->insert([
      ['category_id' => 1, 'brand_id' => 1],
      ['category_id' => 1, 'brand_id' => 2],
      ['category_id' => 1, 'brand_id' => 3],
      ['category_id' => 1, 'brand_id' => 4],
      ['category_id' => 1, 'brand_id' => 5],
      ['category_id' => 1, 'brand_id' => 6],
      ['category_id' => 1, 'brand_id' => 11],
      ['category_id' => 1, 'brand_id' => 12],
      ['category_id' => 1, 'brand_id' => 13],
      ['category_id' => 1, 'brand_id' => 14],
    ]);
    DB::table('category_brands')->insert([
      ['category_id' => 2, 'brand_id' => 3],
      ['category_id' => 2, 'brand_id' => 8],
      ['category_id' => 2, 'brand_id' => 9],
    ]);
    DB::table('category_brands')->insert([
      ['category_id' => 3, 'brand_id' => 3],
      ['category_id' => 3, 'brand_id' => 4],
      ['category_id' => 3, 'brand_id' => 10],
    ]);
    DB::table('category_brands')->insert([
      ['category_id' => 4, 'brand_id' => 2],
      ['category_id' => 4, 'brand_id' => 5],
      ['category_id' => 4, 'brand_id' => 6],
      ['category_id' => 4, 'brand_id' => 8],
    ]);
    DB::table('category_brands')->insert([
      ['category_id' => 5, 'brand_id' => 2],
      ['category_id' => 5, 'brand_id' => 5],
      ['category_id' => 5, 'brand_id' => 6],
      ['category_id' => 5, 'brand_id' => 8],
    ]);
    DB::table('category_brands')->insert([
      ['category_id' => 6, 'brand_id' => 3],
      ['category_id' => 6, 'brand_id' => 7],
      ['category_id' => 6, 'brand_id' => 9],
      ['category_id' => 6, 'brand_id' => 10],
    ]);
  }
}
