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
    Schema::create('brands', function (Blueprint $table) {
      $table->id();
      $table->string("name");
      $table->string("slug");
      $table->string("image")->nullable();
      $table->boolean("is_popular")->default(false);
      /* $table->foreignIdFor(User::class, "created_by")->nullable();
      $table->foreignIdFor(User::class, "updated_by")->nullable();
      $table->softDeletes();
      $table->foreignIdFor(User::class, "deleted_by")->nullable();
      $table->timestamps(); */
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
    Schema::dropIfExists('brands');
  }
};
