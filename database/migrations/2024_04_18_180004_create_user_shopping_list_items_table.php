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
    Schema::create('user_shopping_list_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId("shopping_list_id")->references("id")->on("user_shopping_lists")->onDelete("cascade");
      $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('user_shopping_list_items');
  }
};
