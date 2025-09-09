<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('staff_id')->unique();

            // Role (Spatie handles roles, store name for quick access)
            $table->string('role')->nullable();

            // Foreign keys
            $table->unsignedBigInteger('sub_role_id')->nullable();
            $table->foreign('sub_role_id')->references('id')->on('sub_roles')->nullOnDelete();

            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();

            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();

            // Personal info
            $table->string('name');
            $table->string('corporate_email')->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('address')->nullable();
            $table->string('nationality')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Profile images
            $table->string('avatar')->nullable();
            $table->longText('face_image')->nullable();

            // Clock-in/out
            $table->string('clockin_pin', 255)->nullable();
            $table->boolean('pin_changed')->default(false);
            $table->boolean('password_changed')->default(false);

            $table->rememberToken();
            $table->timestamps();
        });

        // Password reset table (Laravel default)
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Sessions table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('leave_balances');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
        Schema::table('users', function (Blueprint $table) {
            $table->string('clockin_pin', 10)->nullable()->change();
        });
    }
};
