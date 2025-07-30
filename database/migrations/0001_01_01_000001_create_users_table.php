<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('staff_id')->unique(); // Auto-generated WG-1234-2025
    $table->string('name');
    $table->string('corporate_email')->unique(); // For login
    $table->string('email')->nullable();
    $table->string('phone')->nullable();
    $table->string('gender')->nullable(); // male/female/other
    $table->date('date_of_birth')->nullable();
    $table->string('nationality')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->string('avatar')->nullable(); // profile picture
    $table->longText('face_image')->nullable(); // base64 or path
    $table->string('clockin_pin')->nullable();
    $table->boolean('pin_changed')->default(false);
    $table->boolean('password_changed')->default(false);
    $table->rememberToken();
    $table->timestamps();
});

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop dependent tables that reference `users`
        Schema::dropIfExists('sessions'); // This table references `users`
        Schema::dropIfExists('password_reset_tokens'); // This doesn't reference `users`, so can be dropped directly

        // Drop the `users` table last
        Schema::dropIfExists('users');

        // Now drop other tables like documents, departments, and categories (if needed)
        Schema::dropIfExists('documents');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('categories');
    }
};
