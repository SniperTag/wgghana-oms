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
        Schema::create('next_of_kin', function (Blueprint $table) {
            $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade'); // links to employee
    $table->string('name');
    $table->string('address')->nullable();
    $table->date('date_of_birth')->nullable();
    $table->enum('relationship', ['parent','child','spouse','sibling','friend','other']);
    $table->string('phone')->nullable();
    $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('next_of_kin');
    }
};
