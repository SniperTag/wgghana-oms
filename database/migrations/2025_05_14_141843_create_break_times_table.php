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
        Schema::create('break_times', function (Blueprint $table) {
    $table->id();

    // ✅ Add column BEFORE constraint
    $table->foreignId('user_id')->constrained()->onDelete('cascade');

    // ✅ Make sure attendance_records exists and this FK references its id
    $table->unsignedBigInteger('attendance_id')->nullable();

    $table->timestamp('break_start')->nullable();
    $table->timestamp('break_end')->nullable();
    $table->integer('break_duration')->nullable();
    $table->string('break_reason')->nullable();

    $table->timestamps();

    // ✅ Add the foreign key constraint AFTER defining the column
    $table->foreign('attendance_id')
        ->references('id')
        ->on('attendance_records')
        ->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('break_times');
    }
};
