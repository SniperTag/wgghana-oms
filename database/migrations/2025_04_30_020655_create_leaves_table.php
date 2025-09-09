<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('leave_type_id')->constrained('leave_types')->onDelete('cascade');
            $table->foreignId('leave_policy_id')->nullable()->constrained('leave_policies')->nullOnDelete();

            // Dates
            $table->date('start_date');
            $table->date('end_date');

            // Workflow statuses
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            // Supervisor stage
            $table->enum('supervisor_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('supervisor_approved_at')->nullable();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('supervisor_comment')->nullable();
            $table->text('supervisor_rejection_reason')->nullable();

            // HR stage
            $table->enum('hr_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('hr_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('hr_approved_at')->nullable();
            $table->text('hr_rejection_reason')->nullable();

            // Metadata
            $table->boolean('supervisor_required')->default(true);
            $table->text('reason')->nullable();
            $table->string('attachment')->nullable();

            // Audit trail
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
