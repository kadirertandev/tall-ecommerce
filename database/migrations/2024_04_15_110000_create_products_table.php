<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('products', function (Blueprint $table) {
      $table->id();
      $table->string("name");
      $table->string("slug")->unique();
      $table->string("title")->nullable();
      $table->longText("description");
      $table->decimal("price", 10, 2);
      $table->decimal("discount_amount", 10, 2)->nullable();
      $table->string("image");
      $table->foreignIdFor(Category::class)->nullable()->constrained()->nullOnDelete();
      $table->foreignIdFor(Brand::class)->nullable()->constrained()->nullOnDelete();
      $table->foreignId("created_by")->nullable()->references("id")->on("users")->onDelete("cascade");
      $table->foreignId("updated_by")->nullable()->references("id")->on("users")->onDelete("cascade");
      $table->timestamps();
      $table->softDeletes();
      $table->foreignId("deleted_by")->nullable()->references("id")->on("users")->onDelete("cascade");
      /*
      $table->foreignIdFor(User::class, "created_by")->nullable()->nullOnDelete();
      $table->foreignIdFor(User::class, "updated_by")->nullable()->nullOnDelete();
      $table->foreignIdFor(User::class, "deleted_by")->nullable();
      */
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('products');
  }
};
