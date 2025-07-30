<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->uuid('group_uid')->nullable();
            $table->uuid('visitor_uid')->nullable();
            $table->boolean('is_leader')->default(false);
            $table->foreignId('visitor_type_id')->constrained('visitor_types')->onDelete('cascade');
            // Personal Info
            $table->string('full_name');
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nationality')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            // Contact
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            // Visit Details
            $table->string('company')->nullable();
            // Identification
            $table->string('id_type')->nullable();
            $table->string('id_number')->nullable()->unique();
            $table->text('photo')->nullable();
            $table->text('signature')->nullable();

            // Status & Meta
            $table->enum('status', ['active', 'banned'])->default('active');

            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
