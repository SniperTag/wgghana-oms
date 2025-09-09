<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('staff_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Available, On Leave, Absent, etc.
            $table->string('code')->unique()->nullable(); // Short code: AVL, LEV, ABS
            $table->text('description')->nullable(); // Detailed meaning
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Add relation to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('staff_status_id')->nullable()->after('sub_role_id')->constrained('staff_statuses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['staff_status_id']);
            $table->dropColumn('staff_status_id');
        });

        Schema::dropIfExists('staff_statuses');
    }
};
