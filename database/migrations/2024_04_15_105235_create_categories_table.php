<?php

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
    Schema::create('categories', function (Blueprint $table) {
      $table->id();
      $table->string("name");
      $table->string("slug")->unique();
      $table->string("image")->nullable();
      $table->boolean("is_popular")->default(false);
      $table->foreignId("created_by")->nullable()->references("id")->on("users")->onDelete("cascade");
      $table->foreignId("updated_by")->nullable()->references("id")->on("users")->onDelete("cascade");
      $table->timestamps();
      $table->softDeletes();
      $table->foreignId("deleted_by")->nullable()->references("id")->on("users")->onDelete("cascade");
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('categories');
  }
};
