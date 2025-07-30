<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visitor_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('requires_user_id')->default(true); // Require host
            $table->boolean('requires_checklist')->default(false); // PPE/Security screening
            $table->string('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_types');
    }
};
