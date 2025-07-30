<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transfer_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('visitor_id')->constrained()->cascadeOnDelete();
           $table->string('from_host');
            $table->string('to_host');
            $table->text('reason');

            $table->timestamp('transferred_at')->nullable();

            $table->foreignId('transferred_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_logs');
    }
};
