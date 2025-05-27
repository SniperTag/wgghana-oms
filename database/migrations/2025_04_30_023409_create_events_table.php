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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            //department_id
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            //user_id
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('event_type', ['meeting', 'training', 'conference', 'team_building', 'other'])->default('meeting');
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->text('location')->nullable(); // Location of the event
            $table->text('agenda')->nullable(); // Agenda or topics to be discussed
            $table->text('participants')->nullable(); // List of participants or attendees
            $table->text('comments')->nullable(); // Comments or notes related to the event
            $table->string('ip_address')->nullable(); // IP address of the user who created/updated the event
            $table->string('user_agent')->nullable(); // User agent of the device used to create/update the event
            $table->timestamp('deleted_at')->nullable(); // Soft delete timestamp
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete(); // User who deleted the event
            $table->string('attachment')->nullable(); // Optional attachment (e.g., agenda, presentation)
            $table->string('status_comment')->nullable(); // Comments related to the event status
            $table->string('tags')->nullable(); // Tags for event categorization
            $table->string('color')->nullable(); // Color code for event categorization
            $table->string('recurrence')->nullable(); // Recurrence pattern for the event (e.g., daily, weekly)
            $table->timestamp('recurrence_end_date')->nullable(); // End date for the recurrence pattern
            $table->string('recurrence_rule')->nullable(); // Recurrence rule (e.g., "FREQ=WEEKLY;BYDAY=MO,WE,FR")
            $table->string('recurrence_exceptions')->nullable(); // Exceptions to the recurrence pattern (e.g., specific dates to skip)
            $table->string('recurrence_id')->nullable(); // ID of the original event for recurrence
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
        Schema::dropIfExists('events');
    }
};
