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
        Schema::create('sub_roles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
    $table->string('name');   // e.g. 'ceo'
    $table->string('title');            // e.g. 'Chief Executive Officer'
    $table->string('guard_name')->default('web');
    $table->timestamps();

        $table->unique(['department_id', 'name']); // optional, ensures no duplicates in a department

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_roles');
    }
};
