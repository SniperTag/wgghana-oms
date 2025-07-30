<?php

// database/migrations/xxxx_xx_xx_create_appointments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Host
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->dateTime('scheduled_at')->nullable();
            $table->enum('status',['pending','approved','rejected', 'cancelled', 'expired', 'completed'])->default('pending'); // Enum: pending, approved, rejected, completed, cancelled
            $table->text('purpose')->nullable(); // Reason for visit
            $table->text('qr_code')->nullable(); // Store base64 or file path to QR image
            $table->dateTime('check_in_time')->nullable();
            $table->dateTime('check_out_time')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Pivot table: appointment_visitor
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

