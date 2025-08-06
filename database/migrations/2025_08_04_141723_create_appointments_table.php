<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Visitor Info
            $table->unsignedBigInteger('visitor_id')->nullable();
            $table->string('visitor_name');
            $table->string('visitor_phone')->nullable();
            $table->string('visitor_email')->nullable();

            // Host Info
            $table->unsignedBigInteger('host_id'); // user_id of host
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete(); // corrected

            // Appointment Info
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('date');
            $table->time('time');
            $table->enum('meeting_type', ['physical', 'virtual'])->default('physical');
            $table->string('location')->nullable();

            // Status
            $table->enum('status', [
                'pending', 'approved', 'rejected', 'cancelled', 'rescheduled'
            ])->default('pending');

            $table->unsignedBigInteger('created_by')->nullable(); // receptionist/staff
            $table->timestamps();

            // Foreign Keys
            $table->foreign('visitor_id')->references('id')->on('visitors')->onDelete('set null');
            $table->foreign('host_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });

        // Pivot Table for multi-visitor appointments
        Schema::create('appointment_visitor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visitor_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_visitor');
        Schema::dropIfExists('appointments');
    }
};
