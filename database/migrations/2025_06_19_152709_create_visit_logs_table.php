<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visit_logs', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('visitor_id')->constrained('visitors')->cascadeOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();
            $table->string('purpose')->nullable();
            $table->text('visit_reason_detail')->nullable(); // Optional
            $table->foreignId('host_id')->nullable()->constrained('users')->nullOnDelete();
            // Time tracking
            $table->timestamp('check_in_time')->nullable();
            $table->timestamp('check_out_time')->nullable();

            // Additional visit info
            $table->string('badge_number')->nullable();
            $table->text('remarks')->nullable();
            $table->string('location')->nullable(); // e.g. Reception A, Front Desk

            // Status
            $table->enum('status', ['pending', 'checked_in', 'checked_out', 'cancelled'])->default('pending')->index();
            $table->string('visitor_type_id')->nullable(); // e.g. Walk-in, Appointment, Pre-registered
                        $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('rejection_reason')->nullable(); // Reason for rejection or approval


            // Staff tracking
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('checked_out_by')->nullable()->constrained('users')->nullOnDelete();

            // Optional system info
            $table->ipAddress('device_ip')->nullable();
            $table->string('device_name')->nullable();

            // Metadata
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_logs');
    }
};
