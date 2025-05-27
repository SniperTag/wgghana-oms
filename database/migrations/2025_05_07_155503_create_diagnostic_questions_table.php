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
        Schema::create('diagnostic_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                  ->constrained('diagnostic_categories')
                  ->onDelete('cascade');
            $table->text('question');         // The question text
            $table->string('type')->default('rating'); // Type: rating, yes/no, etc.
            $table->integer('weight')->default(1);     // Score weight
            $table->json('options')->nullable();       // JSON options if needed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostic_questions');
    }
};
