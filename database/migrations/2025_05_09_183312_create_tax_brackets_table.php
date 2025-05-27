<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_brackets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('min_amount', 15, 2)->default(0);
            $table->decimal('max_amount', 15, 2)->nullable(); // null = no upper limit
            $table->decimal('rate', 5, 2)->default(0); // e.g. 15.50%
            $table->decimal('fixed_amount', 15, 2)->default(0); // fixed deduction/addition
            $table->string('description')->nullable();

            // Applicability
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_taxable')->default(true);
            $table->boolean('is_deductible')->default(false);
            $table->boolean('is_exempt')->default(false);
            $table->boolean('is_refundable')->default(false);
            $table->boolean('is_withholding')->default(false);

            // Frequency (Period)
            $table->boolean('is_annual')->default(false);
            $table->boolean('is_monthly')->default(false);
            $table->boolean('is_weekly')->default(false);
            $table->boolean('is_daily')->default(false);
            $table->boolean('is_hourly')->default(false);

            // Range rules
            $table->boolean('is_minimum')->default(false);
            $table->boolean('is_maximum')->default(false);

            // Type of tax calculation
            $table->boolean('is_percentage')->default(false); // Rate-based (e.g. 10%)
            $table->boolean('is_fixed')->default(false);      // Fixed amount
            $table->boolean('is_variable')->default(false);   // Based on external logic

            // Tax models
            $table->boolean('is_flat')->default(false);
            $table->boolean('is_graduated')->default(false);
            $table->boolean('is_progressive')->default(false);
            $table->boolean('is_regressive')->default(false);
            $table->boolean('is_proportional')->default(false);
            $table->boolean('is_aggregate')->default(false);

            $table->timestamps();

            // Indexes for faster lookup
            $table->index(['is_active', 'is_default']);
            $table->index(['min_amount', 'max_amount']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_brackets');
    }
};
