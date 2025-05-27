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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->date('attendance_date');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->enum('status',  ['Not Checked In', 'On Time', 'Late', 'Very Late', 'Absent', 'On Leave'])->nullable();
            $table->text('notes')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('device_info')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'attendance_date']); // Ensure one record per user per day
            $table->index('attendance_date'); // Index for quick lookup by date
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
