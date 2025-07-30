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
        Schema::create('break_sessions', function (Blueprint $table) {
            $table->id();

            // Foreign key to users table (staff)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Optional FK to attendance_records if you want to link breaks to attendance
            $table->foreignId('attendance_id')->nullable()->constrained('attendance_records')->onDelete('cascade');

            // Break start and end timestamps
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();

            // Duration in minutes (optional - can be computed on the fly)
            $table->integer('break_duration')->nullable();

            // Break type (Lunch, Prayer, Rest, Other)
            $table->string('break_type')->nullable();

            $table->timestamps();

            // Indexes for faster queries on user_id and attendance_id
            $table->index('user_id');
            $table->index('attendance_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('break_sessions');
    }
};
