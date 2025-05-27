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
        Schema::create('document_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->nullable()->constrained('documents')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->string('viewed_by')->nullable();
            $table->string('viewed_ip')->nullable();
            $table->softDeletes(); // Automatically uses `deleted_at`
            $table->timestamps();

            // Indexes
            $table->index(['document_id', 'user_id']);
            $table->index(['document_id', 'viewed_at']);
            $table->index(['user_id', 'viewed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('document_views');
        Schema::enableForeignKeyConstraints();
    }
};
