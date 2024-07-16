<?php

use App\Models\Product;
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
    Schema::create('product_reviews', function (Blueprint $table) {
      $table->id();
      $table->string("title");
      $table->longText("comment");
      $table->tinyInteger("rating")->default(0);
      $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
      $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
      $table->string("status")->default("evaluating");
      $table->foreignId("updated_by")->nullable()->references("id")->on("users")->onDelete("cascade");
      $table->timestamps();
      $table->softDeletes();
      $table->foreignIdFor(User::class, "deleted_by")->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('product_reviews');
  }
};
