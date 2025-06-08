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
            $table->string('name');
            $table->string('email')->unique();
            $table->string('staff_id')->unique(); // e.g., WG-1234-2025
            $table->string('clockin_pin')->nullable(); // hashed PIN
            $table->boolean('pin_changed')->default(false);

            // Invite User by Token
            $table->string('invite_token')->nullable();
            $table->boolean('is_invited')->default(false);
            $table->string('invite_token_expiry')->nullable();
            $table->string('invite_token_used')->nullable();

            // Department and Position
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->string('phone')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('password_changed')->default(false);
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('avatar')->nullable(); // Path to avatar image
            
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
