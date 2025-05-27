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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key to Users table
            $table->string('meeting_title'); // Title of the meeting
            $table->text('agenda')->nullable(); // Agenda or purpose of the meeting
            $table->dateTime('meeting_date'); // Date and time of the meeting
            //eventually add a foreign key to the users table for the host
            $table->foreignId('host_id')->nullable()->constrained('users')->nullOnDelete(); // Foreign key to Users table (host)
            $table->enum('status', ['scheduled', 'completed', 'canceled'])->default('scheduled'); // Status of the meeting
            $table->text('notes')->nullable(); // Additional notes or comments
            //started time
            $table->time('start_time')->nullable(); // Start time of the meeting
            //ended time
            $table->time('end_time')->nullable(); // End time of the meeting
            //meeting link
            $table->string('meeting_link')->nullable(); // Link to the meeting (e.g., Zoom, Google Meet)
            //meeting type
            $table->enum('meeting_type', ['in_person', 'virtual'])->default('virtual'); // Type of meeting (in-person or virtual)
            //meeting location
            $table->string('location')->nullable(); // Location of the meeting (if in-person)
            //meeting participants
            $table->text('participants')->nullable(); // List of participants (can be JSON or comma-separated)
            //meeting attachment
            $table->string('attachment')->nullable(); // Optional attachment (e.g., agenda, presentation)
            //meeting feedback
            $table->text('feedback')->nullable(); // Feedback or comments from participants
            //meeting follow-up
            $table->text('follow_up')->nullable(); // Follow-up actions or tasks from the meeting
            //reminder
            $table->boolean('reminder')->default(false); // Reminder for the meeting
            //ip address
            $table->string('ip_address')->nullable(); // IP address of the user who created/updated the meeting
            //user agent
            $table->string('user_agent')->nullable(); // User agent of the device used to create/update the meeting
            //deleted at
            $table->timestamp('deleted_at')->nullable(); // Soft delete timestamp
            //deleted by
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete(); // User who deleted the meeting
            //status comment
            $table->string('status_comment')->nullable(); // Comments related to the meeting status
            //tags
            $table->string('tags')->nullable(); // Tags for meeting categorization
            //color
            $table->string('color')->nullable(); // Color code for meeting categorization
            //recurrence
            $table->string('recurrence')->nullable(); // Recurrence pattern for the meeting (e.g., daily, weekly)
            //recurrence end date
            $table->timestamp('recurrence_end_date')->nullable(); // End date for the recurrence pattern
            //recurrence rule
            $table->string('recurrence_rule')->nullable(); // Recurrence rule (e.g., "FREQ=WEEKLY;BYDAY=MO,WE,FR")
            //recurrence exceptions
            $table->string('recurrence_exceptions')->nullable(); // Exceptions to the recurrence pattern (e.g., specific dates to skip)
            //recurrence id
            $table->string('recurrence_id')->nullable(); // ID of the original meeting for recurrence
            //recurrence instance
            $table->string('recurrence_instance')->nullable(); // Instance of the recurrence (e.g., "1st", "2nd")
            //recurrence count
            $table->string('recurrence_count')->nullable(); // Count of occurrences for the recurrence
            //recurrence interval
            $table->string('recurrence_interval')->nullable(); // Interval for the recurrence (e.g., "1" for daily, "2" for every other day)
            //recurrence by day
            $table->string('recurrence_by_day')->nullable(); // Days of the week for the recurrence (e.g., "MO,TU,WE")
            //recurrence by month
            $table->string('recurrence_by_month')->nullable(); // Months for the recurrence (e.g., "1,2,3" for January, February, March)
            //recurrence by year
            $table->string('recurrence_by_year')->nullable(); // Years for the recurrence (e.g., "2025,2026")
            //recurrence by month day
            $table->string('recurrence_by_month_day')->nullable(); // Days of the month for the recurrence (e.g., "1,15" for 1st and 15th)
            //recurrence by week no
            $table->string('recurrence_by_week_no')->nullable(); // Week numbers for the recurrence (e.g., "1,2,3" for 1st, 2nd, and 3rd weeks)
            //recurrence by set pos
            $table->string('recurrence_by_set_pos')->nullable(); // Set position for the recurrence (e.g., "1" for first occurrence)
            //recurrence by hour
            $table->string('recurrence_by_hour')->nullable(); // Hours for the recurrence (e.g., "9,17" for 9 AM and 5 PM)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
