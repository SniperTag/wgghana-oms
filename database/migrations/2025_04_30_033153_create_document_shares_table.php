<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('shared_with')->nullable(); // email or user ID
            $table->string('shared_by')->nullable(); // email or user ID
            $table->string('shared_ip')->nullable(); // IP address of the sharer
            $table->string('shared_user_agent')->nullable(); // User agent of the sharer
            $table->string('shared_referrer')->nullable(); // Referrer URL of the sharer
            $table->timestamp('shared_at')->nullable(); // Timestamp of when the document was shared
            $table->string('access_level')->default('view'); // Access level for the shared document (view/edit)
            $table->string('status')->default('pending'); // Status of the share (pending/accepted/rejected)
            $table->string('note')->nullable(); // Note or message with the share
            $table->string('shared_by_ip')->nullable(); // IP address of the user who shared the document
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_shares');
    }
};
