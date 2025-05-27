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
        Schema::create('meeting_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained('meetings')->onDelete('cascade'); // Foreign key to meetings table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key to users table
            $table->enum('status', ['invited', 'accepted', 'declined'])->default('invited'); // Status of the participant
            $table->timestamp('response_time')->nullable(); // Timestamp when the participant responded
            $table->text('comments')->nullable(); // Optional comments from the participant
            $table->string('ip_address')->nullable(); // IP address of the participant
            $table->string('user_agent')->nullable(); // User agent of the device used by the participant
            $table->timestamp('deleted_at')->nullable(); // Soft delete timestamp
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete(); // User who deleted the participant record
            $table->string('status_comment')->nullable(); // Comments related to the participant's status
            $table->string('tags')->nullable(); // Tags for participant categorization
            $table->string('color')->nullable(); // Color code for participant categorization
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_participants');
    }
};
