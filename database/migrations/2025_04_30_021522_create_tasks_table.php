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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key to Users table
            $table->string('title'); // Title of the task
            $table->text('description')->nullable(); // Description of the task
            $table->date('due_date')->nullable(); // Due date for the task
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium'); // Priority level of the task
            $table->enum('status', ['pending', 'in_progress', 'completed', 'on_hold'])->default('pending'); // Status of the task
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete(); // Foreign key to Projects table
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade'); // User who assigned the task
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade'); // User to whom the task is assigned
            $table->string('attachment')->nullable(); // Optional attachment (e.g., task-related documents)
            $table->text('comments')->nullable(); // Comments related to the task
            $table->string('ip_address')->nullable(); // IP address of the user who created/updated the task
            $table->string('user_agent')->nullable(); // User agent of the device used to create/update the task
            $table->timestamp('deleted_at')->nullable(); // Soft delete timestamp
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete(); // User who deleted the task
            $table->string('status_comment')->nullable(); // Comments related to the task status
            $table->string('tags')->nullable(); // Tags for task categorization
            $table->string('color')->nullable(); // Color code for task categorization
            $table->string('recurrence')->nullable(); // Recurrence pattern for the task (e.g., daily, weekly)
            $table->timestamp('recurrence_end_date')->nullable(); // End date for the recurrence pattern
            $table->string('recurrence_rule')->nullable(); // Recurrence rule (e.g., "FREQ=WEEKLY;BYDAY=MO,WE,FR")
            $table->string('recurrence_exceptions')->nullable(); // Exceptions to the recurrence pattern (e.g., specific dates to skip)
            $table->string('recurrence_id')->nullable(); // ID of the original task for recurrence
            $table->string('recurrence_instance')->nullable(); // Instance of the recurrence (e.g., "1st", "2nd")
            $table->string('recurrence_count')->nullable(); // Count of occurrences for the recurrence
            $table->string('recurrence_interval')->nullable(); // Interval for the recurrence (e.g., "1" for daily, "2" for every other day)
            $table->string('recurrence_by_day')->nullable(); // Days of the week for the recurrence (e.g., "MO,TU,WE")
            $table->string('recurrence_by_month')->nullable(); // Months for the recurrence (e.g., "1,2,3" for January, February, March)
            $table->string('recurrence_by_year')->nullable(); // Years for the recurrence (e.g., "2025,2026")
            $table->string('recurrence_by_month_day')->nullable(); // Days of the month for the recurrence (e.g., "1,15" for 1st and 15th)
            $table->string('recurrence_by_week_no')->nullable(); // Week numbers for the recurrence (e.g., "1,2,3" for 1st, 2nd, and 3rd weeks)
            $table->string('recurrence_by_set_pos')->nullable(); // Set position for the recurrence (e.g., "1" for first occurrence)
            $table->string('recurrence_by_hour')->nullable(); // Hours for the recurrence (e.g., "9,17" for 9 AM and 5 PM)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
