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
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('leave_type_id')->nullable()->constrained('leave_types')->nullOnDelete();
            $table->integer('total_days')->default(22);
            $table->integer('used_days')->default(0);
            $table->integer('remaining_days')->default(0);
            $table->year('year')->nullable(); // Year for which the balance is applicable
            $table->timestamps();
            // Prevent duplicate entries for the same year/type
            $table->unique(['user_id', 'leave_type_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_balances');
    }
};
