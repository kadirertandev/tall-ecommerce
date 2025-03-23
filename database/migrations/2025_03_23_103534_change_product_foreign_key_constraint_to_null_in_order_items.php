<?php

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
    Schema::table('order_items', function (Blueprint $table) {
      $table->dropForeignIdFor(Product::class);

      $table->dropColumn("product_id");
    });

    Schema::table('order_items', function (Blueprint $table) {
      $table->after("order_id", function ($table) {
        $table->bigInteger('product_id')->unsigned()->nullable();
      });
    });

    Schema::table('order_items', function (Blueprint $table) {
      $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('order_items', function (Blueprint $table) {
      //
    });
  }
};
