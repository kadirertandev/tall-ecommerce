<?php

namespace Tests\Feature\Helpers;

use App\Models\Permission;
use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class CreateAdminTest extends TestCase
{
  use RefreshDatabase;

  private User $owner;
  private User $super_admin;
  private User $admin;
  private User $product_editor;
  private User $category_editor;
  private User $brand_editor;
  private User $order_editor;
  private User $review_editor;

  public function setUp(): void
  {
    parent::setUp();

    $this->artisan('db:seed', ['--class' => RolesPermissionsSeeder::class]);
    $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->owner = $this->createAdmin("owner");
    $this->super_admin = $this->createAdmin("super_admin");
    $this->admin = $this->createAdmin("admin");
    $this->product_editor = $this->createAdmin("product_editor");
    $this->category_editor = $this->createAdmin("category_editor");
    $this->brand_editor = $this->createAdmin("brand_editor");
    $this->order_editor = $this->createAdmin("order_editor");
    $this->review_editor = $this->createAdmin("review_editor");
  }

  public function test_throws_exception_when_role_name_is_invalid()
  {
    $this->expectException(RoleDoesNotExist::class);

    $invalidAdmin = $this->createAdmin("invalidRoleName");
  }

  public function test_creates_admin_with_owner_role_and_related_permissions()
  {
    $ownerPermissions = Permission::all()->pluck("name");

    $this->assertInstanceOf(User::class, $this->owner);
    $this->assertEquals("owner", $this->owner->getRoleName());
    $this->assertDatabaseHas("users", [
      "email" => $this->owner->email,
      "is_admin" => true
    ]);

    foreach ($ownerPermissions as $permission) {
      $this->assertTrue($this->owner->can($permission));
    }
  }

  public function test_creates_admin_with_super_admin_role_and_related_permissions()
  {
    $prohibitedPermissions = [
      "create admins",
      "edit admins",
      "delete admins",
      "force delete admins"
    ];

    $superAdminPermissions = Permission::all()->pluck("name", "name")->except($prohibitedPermissions);

    $this->assertInstanceOf(User::class, $this->super_admin);
    $this->assertEquals("super_admin", $this->super_admin->getRoleName());
    $this->assertDatabaseHas("users", [
      "email" => $this->super_admin->email,
      "is_admin" => true
    ]);

    foreach ($superAdminPermissions as $permission) {
      $this->assertTrue($this->super_admin->can($permission));
    }

    foreach ($prohibitedPermissions as $permission) {
      $this->assertTrue($this->super_admin->cannot($permission));
    }
  }

  public function test_creates_admin_with_admin_role_and_related_permissions()
  {
    $prohibitedPermissions = [
      "create admins",
      "edit admins",
      "delete admins",
      "force delete admins",
      "assign role",
      "force delete products",
      "force delete categories",
      "force delete brands"
    ];

    $adminPermissions = Permission::all()->pluck("name", "name")->except($prohibitedPermissions);

    $this->assertInstanceOf(User::class, $this->admin);
    $this->assertEquals("admin", $this->admin->getRoleName());
    $this->assertDatabaseHas("users", [
      "email" => $this->admin->email,
      "is_admin" => true
    ]);

    foreach ($adminPermissions as $permission) {
      $this->assertTrue($this->admin->can($permission));
    }

    foreach ($prohibitedPermissions as $permission) {
      $this->assertTrue($this->admin->cannot($permission));
    }
  }

  public function test_creates_admin_with_product_editor_role_and_related_permissions()
  {
    $allowedPermissions = [
      "view dashboard",
      "view products",
      "create products",
      "edit products",
      "delete products"
    ];

    $prohibitedPermissions = Permission::all()->pluck("name", "name")->except($allowedPermissions);

    $this->assertInstanceOf(User::class, $this->product_editor);
    $this->assertEquals("product_editor", $this->product_editor->getRoleName());
    $this->assertDatabaseHas("users", [
      "email" => $this->product_editor->email,
      "is_admin" => true
    ]);

    foreach ($allowedPermissions as $permission) {
      $this->assertTrue($this->product_editor->can($permission));
    }

    foreach ($prohibitedPermissions as $permission) {
      $this->assertTrue($this->product_editor->cannot($permission));
    }
  }

  public function test_creates_admin_with_category_editor_role_and_related_permissions()
  {
    $allowedPermissions = [
      "view dashboard",
      "view categories",
      "create categories",
      "edit categories",
      "delete categories"
    ];

    $prohibitedPermissions = Permission::all()->pluck("name", "name")->except($allowedPermissions);

    $this->assertInstanceOf(User::class, $this->category_editor);
    $this->assertEquals("category_editor", $this->category_editor->getRoleName());
    $this->assertDatabaseHas("users", [
      "email" => $this->category_editor->email,
      "is_admin" => true
    ]);

    foreach ($allowedPermissions as $permission) {
      $this->assertTrue($this->category_editor->can($permission));
    }

    foreach ($prohibitedPermissions as $permission) {
      $this->assertTrue($this->category_editor->cannot($permission));
    }
  }

  public function test_creates_admin_with_brand_editor_role_and_related_permissions()
  {
    $allowedPermissions = [
      "view dashboard",
      "view brands",
      "create brands",
      "edit brands",
      "delete brands"
    ];

    $prohibitedPermissions = Permission::all()->pluck("name", "name")->except($allowedPermissions);

    $this->assertInstanceOf(User::class, $this->brand_editor);
    $this->assertEquals("brand_editor", $this->brand_editor->getRoleName());
    $this->assertDatabaseHas("users", [
      "email" => $this->brand_editor->email,
      "is_admin" => true
    ]);

    foreach ($allowedPermissions as $permission) {
      $this->assertTrue($this->brand_editor->can($permission));
    }

    foreach ($prohibitedPermissions as $permission) {
      $this->assertTrue($this->brand_editor->cannot($permission));
    }
  }

  public function test_creates_admin_with_order_editor_role_and_related_permissions()
  {
    $allowedPermissions = [
      "view dashboard",
      "view orders",
      "edit orders"
    ];

    $prohibitedPermissions = Permission::all()->pluck("name", "name")->except($allowedPermissions);

    $this->assertInstanceOf(User::class, $this->order_editor);
    $this->assertEquals("order_editor", $this->order_editor->getRoleName());
    $this->assertDatabaseHas("users", [
      "email" => $this->order_editor->email,
      "is_admin" => true
    ]);

    foreach ($allowedPermissions as $permission) {
      $this->assertTrue($this->order_editor->can($permission));
    }

    foreach ($prohibitedPermissions as $permission) {
      $this->assertTrue($this->order_editor->cannot($permission));
    }
  }

  public function test_creates_admin_with_review_editor_role_and_related_permissions()
  {
    $allowedPermissions = [
      "view dashboard",
      "view reviews",
      "edit reviews",
      "delete reviews",
      "force delete reviews"
    ];

    $prohibitedPermissions = Permission::all()->pluck("name", "name")->except($allowedPermissions);

    $this->assertInstanceOf(User::class, $this->review_editor);
    $this->assertEquals("review_editor", $this->review_editor->getRoleName());
    $this->assertDatabaseHas("users", [
      "email" => $this->review_editor->email,
      "is_admin" => true
    ]);

    foreach ($allowedPermissions as $permission) {
      $this->assertTrue($this->review_editor->can($permission));
    }

    foreach ($prohibitedPermissions as $permission) {
      $this->assertTrue($this->review_editor->cannot($permission));
    }
  }
}
