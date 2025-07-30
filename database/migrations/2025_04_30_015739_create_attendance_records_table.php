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

            // Reference to the user (staff)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Department reference (optional)
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');

            // Date of the attendance record
            $table->date('attendance_date');

            // Clock-in and clock-out times (nullable in case user didn't check in/out)
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();

            // Attendance status with possible values, nullable if not yet determined
            $table->enum('status',  ['Not Checked In', 'On Time', 'Late', 'Very Late', 'Absent', 'On Leave'])->nullable();

            // Store face snapshot, you might want to keep this as text (base64) or a file path
            $table->longtext('face_snapshot')->nullable();
            
            // Additional notes if any
            $table->text('notes')->nullable();

            // IP and device info for audit/logging purposes
            $table->string('ip_address')->nullable();
            $table->string('device_info')->nullable();

            // Laravel's created_at and updated_at timestamps
            $table->timestamps();

            // Unique constraint: one attendance record per user per day
            $table->unique(['user_id', 'attendance_date']);

            // Index to speed up queries filtering by attendance_date
            $table->index('attendance_date');
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
