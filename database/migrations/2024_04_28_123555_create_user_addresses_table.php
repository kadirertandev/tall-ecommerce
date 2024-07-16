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
    Schema::create('user_addresses', function (Blueprint $table) {
      $table->id();
      $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
      $table->string("title");
      $table->string("city");
      $table->string("district");
      $table->string("neighborhood");
      $table->string("address_line");
      $table->boolean("is_default")->default(false);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('user_addresses');
  }
};
