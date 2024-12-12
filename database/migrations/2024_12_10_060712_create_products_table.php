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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Sr. No (Auto Increment ID)
            $table->string('name'); // Name of the item
            $table->string('sku')->unique();
            $table->decimal('price', 10, 2); // SKU (Stock Keeping Unit)
            $table->string('image')->nullable(); // Image file path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};