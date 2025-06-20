<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shop_item_shop_item_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('shop_item_category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_item_shop_item_category');
    }
};