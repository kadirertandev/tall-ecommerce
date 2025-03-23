<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('cart_items', function (Blueprint $table) {
      $table->dropColumn(["price", "discount_amount", "item_total_price"]);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('cart_items', function (Blueprint $table) {
      $table->decimal("price", 10, 2);
      $table->decimal("discount_amount", 10, 2)->nullable();
      $table->decimal("item_total_price", 10, 2);
    });
  }
};
