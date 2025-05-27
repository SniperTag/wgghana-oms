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
        Schema::create('diagnostic_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnostic_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('question_id')
                  ->constrained('diagnostic_questions')
                  ->onDelete('cascade');
            $table->foreignId('user_id')      // The user who answered
                  ->constrained()
                  ->onDelete('cascade');
            $table->text('answer');           // The actual answer (could be string or JSON)
            $table->float('score')->default(0); // Calculated score for this answer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostic_answers');
    }
};
