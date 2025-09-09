<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Create 'appointments' table if it doesn't exist
        if (!Schema::hasTable('appointments')) {
            Schema::create('appointments', function (Blueprint $table) {
                $table->id();

                // Visitor Info
                $table->unsignedBigInteger('visitor_id')->nullable();
                $table->string('visitor_name');
                $table->string('visitor_phone')->nullable();
                $table->string('visitor_email')->nullable();

                // Host Info
                $table->unsignedBigInteger('host_id');
                $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();

                // Appointment Info
                $table->string('title');
                $table->text('description')->nullable();
                $table->date('date');
                $table->time('time');
                $table->enum('meeting_type', ['physical', 'virtual'])->default('physical');
                $table->string('location')->nullable();

                // Status
                $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'rescheduled'])->default('pending');
                $table->text('rejected_reason')->nullable();
                $table->boolean('was_rescheduled')->default(false);
                $table->timestamp('rescheduled_at')->nullable();
                $table->unsignedBigInteger('rescheduled_by')->nullable();
                $table->string('reschedule_reason')->nullable();

                // Created by
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                // Foreign Keys
                $table->foreign('visitor_id')->references('id')->on('visitors')->onDelete('set null');
                $table->foreign('host_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                $table->foreign('rescheduled_by')->references('id')->on('users')->onDelete('set null');
            });
        }

        // Pivot table for multi-visitor appointments
        if (!Schema::hasTable('appointment_visitor')) {
            Schema::create('appointment_visitor', function (Blueprint $table) {
                $table->id();
                $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
                $table->foreignId('visitor_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Drop pivot table first
        Schema::dropIfExists('appointment_visitor');

        // Drop 'appointments' table safely
        if (Schema::hasTable('appointments')) {
            Schema::table('appointments', function (Blueprint $table) {
                if (Schema::hasColumn('appointments', 'rescheduled_by')) {
                    $table->dropForeign(['rescheduled_by']);
                }
                $table->dropColumn(['rejected_reason', 'was_rescheduled', 'rescheduled_at', 'rescheduled_by', 'reschedule_reason']);
            });
            Schema::dropIfExists('appointments');
        }
    }
};
