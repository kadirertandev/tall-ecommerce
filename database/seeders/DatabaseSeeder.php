<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\CategoryBrand;
use App\Models\City;
use App\Models\DailyDealProduct;
use App\Models\District;
use App\Models\Permission;
use App\Models\ProductReview;
use App\Models\Role;
use App\Models\User;
use App\Models\WeeklyDealProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */

  public function generate_slug(string $name)
  {
    return Str::slug($name);
  }
  public function run(): void
  {
    \App\Models\User::factory(10)->create();

    // \App\Models\User::factory()->create([
    //     'name' => 'Test User',
    //     'email' => 'test@example.com',
    // ]);

    $role_owner = Role::create(["name" => "owner"]);
    $role_super_admin = Role::create(["name" => "super_admin"]);
    $role_admin = Role::create(["name" => "admin"]);
    $role_moderator = Role::create(["name" => "moderator"]);
    $role_product_editor = Role::create(["name" => "product_editor"]);
    $role_category_editor = Role::create(["name" => "category_editor"]);
    $role_brand_editor = Role::create(["name" => "brand_editor"]);
    $role_order_editor = Role::create(["name" => "order_editor"]);
    $role_review_editor = Role::create(["name" => "review_editor"]);

    $permission_view_admins = Permission::create(["name" => "view admins"]);
    $permission_create_admins = Permission::create(["name" => "create admins"]);
    $permission_edit_admins = Permission::create(["name" => "edit admins"]);
    $permission_delete_admins = Permission::create(["name" => "delete admins"]);
    $permission_force_delete_admins = Permission::create(["name" => "force delete admins"]);
    $permission_assign_role = Permission::create(["name" => "assign role"]);

    $permission_view_products = Permission::create(["name" => "view products"]);
    $permission_create_products = Permission::create(["name" => "create products"]);
    $permission_edit_products = Permission::create(["name" => "edit products"]);
    $permission_delete_products = Permission::create(["name" => "delete products"]);
    $permission_force_delete_products = Permission::create(["name" => "force delete products"]);

    $permission_view_categories = Permission::create(["name" => "view categories"]);
    $permission_create_categories = Permission::create(["name" => "create categories"]);
    $permission_edit_categories = Permission::create(["name" => "edit categories"]);
    $permission_delete_categories = Permission::create(["name" => "delete categories"]);
    $permission_force_delete_categories = Permission::create(["name" => "force delete categories"]);

    $permission_view_brands = Permission::create(["name" => "view brands"]);
    $permission_create_brands = Permission::create(["name" => "create brands"]);
    $permission_edit_brands = Permission::create(["name" => "edit brands"]);
    $permission_delete_brands = Permission::create(["name" => "delete brands"]);
    $permission_force_delete_brands = Permission::create(["name" => "force delete brands"]);

    $permission_view_customers = Permission::create(["name" => "view customers"]);
    $permission_delete_customers = Permission::create(["name" => "delete customers"]);
    $permission_force_delete_customers = Permission::create(["name" => "force delete customers"]);

    $permission_view_orders = Permission::create(["name" => "view orders"]);
    $permission_edit_orders = Permission::create(["name" => "edit orders"]);

    $permission_view_reviews = Permission::create(["name" => "view reviews"]);
    $permission_edit_reviews = Permission::create(["name" => "edit reviews"]);
    $permission_delete_reviews = Permission::create(["name" => "delete reviews"]);
    $permission_force_delete_reviews = Permission::create(["name" => "force delete reviews"]);

    $role_owner->assignPermissions(Permission::all());
    $role_super_admin->assignPermissions(Permission::all()->except([
      $permission_create_admins->id,
      $permission_edit_admins->id,
      $permission_delete_admins->id,
      $permission_force_delete_admins->id,
    ]));
    $role_admin->assignPermissions([
      'view admins',

      'view products',
      'create products',
      'edit products',
      'delete products',

      'view categories',
      'create categories',
      'edit categories',
      'delete categories',

      'view brands',
      'create brands',
      'edit brands',
      'delete brands',

      'view customers',
      'delete customers',

      'view orders',
      'edit orders',

      'view reviews',
      'edit reviews',
      'delete reviews',
      'force delete reviews'
    ]);
    $role_moderator->assignPermissions([
      'view products',
      'edit products',

      'view categories',
      'edit categories',

      'view brands',
      'edit brands',

      'view customers',
      'view orders',

      'view reviews',
      'edit reviews',
      'delete reviews',
      'force delete reviews',
    ]);
    $role_product_editor->assignPermissions([
      'view products',
      'create products',
      'edit products',
      'delete products',
    ]);
    $role_category_editor->assignPermissions([
      'view categories',
      'create categories',
      'edit categories',
      'delete categories',
    ]);
    $role_brand_editor->assignPermissions([
      'view brands',
      'create brands',
      'edit brands',
      'delete brands',
    ]);
    $role_order_editor->assignPermissions([
      'view orders',
      'edit orders',
    ]);
    $role_review_editor->assignPermissions([
      'view reviews',
      'create reviews',
      'edit reviews',
      'delete reviews',
    ]);

    $owner = User::create([
      'first_name' => "owner",
      'last_name' => "owner",
      'email' => "owner@test.com",
      'is_admin' => 1,
      'password' => bcrypt("asdfasdf")
    ]);
    $owner->assignRole($role_owner);

    $super_admin = User::create([
      'first_name' => "super_admin",
      'last_name' => "super_admin",
      'email' => "super_admin@test.com",
      'is_admin' => 1,
      'password' => bcrypt("asdfasdf")
    ]);
    $super_admin->assignRole($role_super_admin);

    $admin = User::create([
      'first_name' => "admin",
      'last_name' => "admin",
      'email' => "admin@test.com",
      'is_admin' => 1,
      'password' => bcrypt("asdfasdf")
    ]);
    // $admin->roles()->attach($role_admin);
    $admin->assignRole($role_admin);

    $product_editor = User::create([
      'first_name' => "product_editor",
      'last_name' => "product_editor",
      'email' => "product_editor@test.com",
      'is_admin' => 1,
      'password' => bcrypt("asdfasdf")
    ]);
    // $product_editor->roles()->attach($role_product_editor);
    $product_editor->assignRole($role_product_editor);

    $category_editor = User::create([
      'first_name' => "category_editor",
      'last_name' => "category_editor",
      'email' => "category_editor@test.com",
      'is_admin' => 1,
      'password' => bcrypt("asdfasdf")
    ]);
    // $category_editor->roles()->attach($role_category_editor);
    $category_editor->assignRole($role_category_editor);

    $brand_editor = User::create([
      'first_name' => "brand_editor",
      'last_name' => "brand_editor",
      'email' => "brand_editor@test.com",
      'is_admin' => 1,
      'password' => bcrypt("asdfasdf")
    ]);
    // $brand_editor->roles()->attach($role_brand_editor);
    $brand_editor->assignRole($role_brand_editor);

    $order_editor = User::create([
      'first_name' => "order_editor",
      'last_name' => "order_editor",
      'email' => "order_editor@test.com",
      'is_admin' => 1,
      'password' => bcrypt("asdfasdf")
    ]);
    // $order_editor->roles()->attach($role_order_editor);
    $order_editor->assignRole($role_order_editor);

    $review_editor = User::create([
      'first_name' => "review_editor",
      'last_name' => "review_editor",
      'email' => "review_editor@test.com",
      'is_admin' => 1,
      'password' => bcrypt("asdfasdf")
    ]);
    // $review_editor->roles()->attach($role_review_editor);
    $review_editor->assignRole($role_review_editor);

    User::create([
      'first_name' => "kadir",
      'last_name' => "ertan",
      'email' => "kadir@test.com",
      'is_admin' => 0,
      'password' => bcrypt("asdfasdf")
    ]);

    #Categories
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

    #Brands
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

    #CategoryBrands
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

    #Products
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

    #DailyDealProducts
    DailyDealProduct::create([
      "product_id" => 1,
      // "start_date" => date("Y-m-d H:i:s"),
      "start_date" => Carbon::today()->startOfDay(),
      "end_date" => Carbon::today()->addDay()->startOfDay(),
      "discount_amount" => 1200
    ]);
    DailyDealProduct::create([
      "product_id" => 2,
      "start_date" => Carbon::today()->startOfDay(),
      "end_date" => Carbon::today()->addDay()->startOfDay(),
      "discount_amount" => 500
    ]);
    DailyDealProduct::create([
      "product_id" => 3,
      "start_date" => Carbon::today()->startOfDay(),
      "end_date" => Carbon::today()->addDay()->startOfDay(),
      "discount_amount" => 700
    ]);

    #WeeklyDealProducts
    WeeklyDealProduct::create([
      "product_id" => 4,
      "start_date" => Carbon::today()->subDays(1)->startOfDay(),
      "end_date" => Carbon::today()->subDays(1)->addWeek()->startOfDay(),
      "discount_amount" => 700
    ]);
    WeeklyDealProduct::create([
      "product_id" => 5,
      "start_date" => Carbon::today()->subDays(1)->startOfDay(),
      "end_date" => Carbon::today()->subDays(1)->addWeek()->startOfDay(),
      "discount_amount" => 700
    ]);
    WeeklyDealProduct::create([
      "product_id" => 6,
      "start_date" => Carbon::today()->subDays(1)->startOfDay(),
      "end_date" => Carbon::today()->subDays(1)->addWeek()->startOfDay(),
      "discount_amount" => 700
    ]);

    #Product Reviews
    ProductReview::create([
      "title" => "Thinking to buy another one!",
      "comment" => "This is my third Invicta Pro Diver. They are just fantastic value for money. This one arrived yesterday and the first thing I did was set the time, popped on an identical strap from another Invicta and went in the shower with it to test the waterproofing.... No problems.",
      "rating" => 4,
      "user_id" => 1,
      "product_id" => 1,
      "status" => "approved"
    ]);
    ProductReview::create([
      "title" => "Impressed with the quality!",
      "comment" => "This product exceeded my expectations. The build quality is excellent, and it performs flawlessly. I highly recommend it!",
      "rating" => 3,
      "user_id" => 2,
      "product_id" => 1,
      "status" => "approved"
    ]);

    ProductReview::create([
      "title" => "Great buy for the price",
      "comment" => "I was hesitant at first, but I'm glad I took the chance on this product. It offers fantastic value for the price and has become a staple in my daily routine.",
      "rating" => 1,
      "user_id" => 3,
      "product_id" => 1,
      "status" => "approved"
    ]);

    ProductReview::create([
      "title" => "A few minor issues, but overall satisfied",
      "comment" => "While there are a couple of minor inconveniences, the product still delivers a great user experience. I would recommend it with a few reservations.",
      "rating" => 3,
      "user_id" => 4,
      "product_id" => 1,
      "status" => "approved"
    ]);

    ProductReview::create([
      "title" => "Needs improvement",
      "comment" => "Unfortunately, I'm disappointed with this product. It fell short of my expectations in several areas. I hope the manufacturer can address these issues in future versions.",
      "rating" => 2,
      "user_id" => 1,
      "product_id" => 1,
      "status" => "approved"
    ]);

    ProductReview::create([
      "title" => "Just perfect for my needs",
      "comment" => "This product is exactly what I was looking for! It checks all the boxes and has made my life much easier. I'm incredibly happy with this purchase.",
      "rating" => 3,
      "user_id" => 1,
      "product_id" => 2,
      "status" => "approved"
    ]);

    ProductReview::create([
      "title" => "Needs improvement",
      "comment" => "Unfortunately, I'm disappointed with this product. It fell short of my expectations in several areas. I hope the manufacturer can address these issues in future versions.",
      "rating" => 4,
      "user_id" => 1,
      "product_id" => 2,
      "status" => "approved"
    ]);
  }
}
