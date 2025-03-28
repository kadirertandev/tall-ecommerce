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
    Schema::table('user_shopping_lists', function (Blueprint $table) {
      $table->dropForeignIdFor(User::class);
      $table->dropColumn("user_id");
    });

    Schema::table('user_shopping_list_items', function (Blueprint $table) {
      $table->dropForeign(["shopping_list_id"]);
      $table->dropColumn("shopping_list_id");

      $table->dropForeignIdFor(Product::class);
      $table->dropColumn("product_id");
    });

    Schema::dropIfExists('user_shopping_lists');
    Schema::dropIfExists('user_shopping_list_items');
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    //
  }
};
