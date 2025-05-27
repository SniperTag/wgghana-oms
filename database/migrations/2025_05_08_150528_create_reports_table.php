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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Staff member submitting the report
            $table->enum('report_type', ['daily', 'weekly', 'monthly']);
            $table->text('content'); // Task summaries or activities
            $table->date('report_date');
            $table->enum('status', ['submitted', 'reviewed', 'approved', 'pending'])->default('submitted');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null'); // Supervisor/HR reviewer
            $table->text('review_comments')->nullable(); // Comments from supervisor/HR
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
