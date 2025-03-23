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
    Schema::table('order_items', function (Blueprint $table) {
      $table->after("product_id", function ($table) {
        $table->string("product_name");
        $table->string("product_image");
      });
      $table->after("price", function ($table) {
        $table->decimal("original_product_price", 10, 2);
      });
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('order_items', function (Blueprint $table) {
      $table->dropColumn(["product_name", "product_image", "original_product_price"]);
    });
  }
};
