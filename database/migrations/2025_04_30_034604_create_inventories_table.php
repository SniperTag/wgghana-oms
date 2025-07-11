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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('sku')->unique()->nullable(); // Stock Keeping Unit
            $table->enum('inventory_type', ['hardware', 'software', 'furniture', 'vehicle', 'stationery', 'consumable'])->default('hardware');
            $table->integer('quantity')->default(0);
            $table->integer('reorder_level')->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
