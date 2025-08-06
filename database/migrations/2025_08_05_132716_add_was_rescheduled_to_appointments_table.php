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
        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('was_rescheduled')->default(false)->after('status');
            $table->timestamp('rescheduled_at')->nullable()->after('was_rescheduled');
            $table->unsignedBigInteger('rescheduled_by')->nullable()->after('rescheduled_at');
            $table->string('reschedule_reason')->nullable()->after('rescheduled_by');

              $table->foreign('rescheduled_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['rescheduled_by']);
            $table->dropColumn(['was_rescheduled', 'rescheduled_at', 'rescheduled_by', 'reschedule_reason']);
        });
    }
};
