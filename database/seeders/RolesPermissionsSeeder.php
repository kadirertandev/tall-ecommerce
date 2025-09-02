<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $roleOwner = Role::firstOrCreate(["name" => "owner"]);
    $roleSuperAdmin = Role::firstOrCreate(["name" => "super_admin"]);
    $roleAdmin = Role::firstOrCreate(["name" => "admin"]);
    $roleProductEditor = Role::firstOrCreate(["name" => "product_editor"]);
    $roleCategoryEditor = Role::firstOrCreate(["name" => "category_editor"]);
    $roleBrandEditor = Role::firstOrCreate(["name" => "brand_editor"]);
    $roleOrderEditor = Role::firstOrCreate(["name" => "order_editor"]);
    $roleReviewEditor = Role::firstOrCreate(["name" => "review_editor"]);

    $permissionViewDashboard = Permission::firstOrCreate(["name" => "view dashboard"]);

    $permissionViewAdmins = Permission::firstOrCreate(["name" => "view admins"]);
    $permissionCreateAdmins = Permission::firstOrCreate(["name" => "create admins"]);
    $permissionEditAdmins = Permission::firstOrCreate(["name" => "edit admins"]);
    $permissionDeleteAdmins = Permission::firstOrCreate(["name" => "delete admins"]);
    $permissionForceDeleteAdmins = Permission::firstOrCreate(["name" => "force delete admins"]);
    $permissionAssignRole = Permission::firstOrCreate(["name" => "assign role"]);

    $permissionViewProducts = Permission::firstOrCreate(["name" => "view products"]);
    $permissionCreateProducts = Permission::firstOrCreate(["name" => "create products"]);
    $permissionEditProducts = Permission::firstOrCreate(["name" => "edit products"]);
    $permissionDeleteProducts = Permission::firstOrCreate(["name" => "delete products"]);
    $permissionForceDeleteProducts = Permission::firstOrCreate(["name" => "force delete products"]);

    $permissionViewCategories = Permission::firstOrCreate(["name" => "view categories"]);
    $permissionCreateCategories = Permission::firstOrCreate(["name" => "create categories"]);
    $permissionEditCategories = Permission::firstOrCreate(["name" => "edit categories"]);
    $permissionDeleteCategories = Permission::firstOrCreate(["name" => "delete categories"]);
    $permissionForceDeleteCategories = Permission::firstOrCreate(["name" => "force delete categories"]);

    $permissionViewBrands = Permission::firstOrCreate(["name" => "view brands"]);
    $permissionCreateBrands = Permission::firstOrCreate(["name" => "create brands"]);
    $permissionEditBrands = Permission::firstOrCreate(["name" => "edit brands"]);
    $permissionDeleteBrands = Permission::firstOrCreate(["name" => "delete brands"]);
    $permissionForceDeleteBrands = Permission::firstOrCreate(["name" => "force delete brands"]);

    $permissionViewCustomers = Permission::firstOrCreate(["name" => "view customers"]);

    $permissionViewOrders = Permission::firstOrCreate(["name" => "view orders"]);
    $permissionEditOrders = Permission::firstOrCreate(["name" => "edit orders"]);

    $permissionViewReviews = Permission::firstOrCreate(["name" => "view reviews"]);
    $permissionEditReviews = Permission::firstOrCreate(["name" => "edit reviews"]);
    $permissionDeleteReviews = Permission::firstOrCreate(["name" => "delete reviews"]);
    $permissionForceDeleteReviews = Permission::firstOrCreate(["name" => "force delete reviews"]);

    $roleOwner->syncPermissions(Permission::all());

    $roleSuperAdmin->syncPermissions(Permission::all()->except([
      $permissionCreateAdmins->id,
      $permissionEditAdmins->id,
      $permissionDeleteAdmins->id,
      $permissionForceDeleteAdmins->id
    ]));

    $roleAdmin->syncPermissions(Permission::all()->except([
      $permissionCreateAdmins->id,
      $permissionEditAdmins->id,
      $permissionDeleteAdmins->id,
      $permissionForceDeleteAdmins->id,
      $permissionAssignRole->id,
      $permissionForceDeleteProducts->id,
      $permissionForceDeleteCategories->id,
      $permissionForceDeleteBrands->id
    ]));

    $roleProductEditor->syncPermissions([
      $permissionViewDashboard,
      $permissionViewProducts,
      $permissionCreateProducts,
      $permissionEditProducts,
      $permissionDeleteProducts,
    ]);

    $roleCategoryEditor->syncPermissions([
      $permissionViewDashboard,
      $permissionViewCategories,
      $permissionCreateCategories,
      $permissionEditCategories,
      $permissionDeleteCategories,
    ]);

    $roleBrandEditor->syncPermissions([
      $permissionViewDashboard,
      $permissionViewBrands,
      $permissionCreateBrands,
      $permissionEditBrands,
      $permissionDeleteBrands,
    ]);

    $roleOrderEditor->syncPermissions([
      $permissionViewDashboard,
      $permissionViewOrders,
      $permissionEditOrders,
    ]);

    $roleReviewEditor->syncPermissions([
      $permissionViewDashboard,
      $permissionViewReviews,
      $permissionEditReviews,
      $permissionDeleteReviews,
      $permissionForceDeleteReviews,
    ]);

    $owner = User::create([
      'first_name' => "owner",
      'last_name' => "owner",
      'email' => "owner@test.com",
      'is_admin' => 1,
      'password' => bcrypt("asdfasdf")
    ]);
    $owner->syncRoles($roleOwner);

    $super_admin = User::create([
      'first_name' => "super_admin",
      'last_name' => "super_admin",
      'email' => "super_admin@test.com",
      'is_admin' => 1,
      'password' => bcrypt("asdfasdf")
    ]);
    $super_admin->syncRoles($roleSuperAdmin);

    $admin = User::create([
      'first_name' => "admin",
      'last_name' => "admin",
      'email' => "admin@test.com",
      'is_admin' => 1,
      'password' => bcrypt("asdfasdf")
    ]);
    $admin->syncRoles($roleAdmin);

    $product_editor = User::create([
      'first_name' => "product_editor",
      'last_name' => "product_editor",
      'email' => "product_editor@test.com",
      'is_admin' => 1,
      'password' => bcrypt("asdfasdf")
    ]);
    $product_editor->syncRoles($roleProductEditor);

    $category_editor = User::create([
      'first_name' => "category_editor",
      'last_name' => "category_editor",
      'email' => "category_editor@test.com",
      'is_admin' => 1,
      'password' => bcrypt("asdfasdf")
    ]);
    $category_editor->syncRoles($roleCategoryEditor);

    $brand_editor = User::create([
      'first_name' => "brand_editor",
      'last_name' => "brand_editor",
      'email' => "brand_editor@test.com",
      'is_admin' => 1,
      'password' => bcrypt("asdfasdf")
    ]);
    $brand_editor->syncRoles($roleBrandEditor);

    $order_editor = User::create([
      'first_name' => "order_editor",
      'last_name' => "order_editor",
      'email' => "order_editor@test.com",
      'is_admin' => 1,
      'password' => bcrypt("asdfasdf")
    ]);
    $order_editor->syncRoles($roleOrderEditor);

    $review_editor = User::create([
      'first_name' => "review_editor",
      'last_name' => "review_editor",
      'email' => "review_editor@test.com",
      'is_admin' => 1,
      'password' => bcrypt("asdfasdf")
    ]);
    $review_editor->syncRoles($roleReviewEditor);
  }
}
