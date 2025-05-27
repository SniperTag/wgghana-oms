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
        Schema::create('leave_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Unique name for the leave policy
            $table->integer('total_days')->default(22); // Total number of leave days
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete(); // Role associated with the policy
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete(); // Department associated with the policy
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_policies');
    }
};
