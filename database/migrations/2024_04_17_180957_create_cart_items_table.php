<?php

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('cart_items', function (Blueprint $table) {
      $table->id();
      $table->foreignIdFor(Cart::class)->constrained()->cascadeOnDelete();
      $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
      $table->decimal("price", 10, 2);
      $table->decimal("discount_amount", 10, 2)->nullable();
      $table->integer("quantity");
      $table->decimal("item_total_price", 10, 2);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('cart_items');
  }
};
