<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableName = config('activitylog.table_name', 'activity_log');
        $connection = config('activitylog.database_connection', config('database.default'));

        Schema::connection($connection)->create($tableName, function (Blueprint $table) {
            $table->uuid('id')->primary(); // Use UUID as primary key

            // Optional log name
            $table->string('log_name')->nullable();

            // Activity description
            $table->text('description');

            // Polymorphic subject (affected model)
            $table->nullableMorphs('subject'); // subject_type + subject_id + index

            // Polymorphic causer (initiator)
            $table->nullableMorphs('causer'); // causer_type + causer_id + index
             // Add batch_uuid column here, nullable UUID
            $table->uuid('batch_uuid')->nullable();

            // Optional event type (e.g., created, updated)
            $table->string('event')->nullable();

            // Properties (extra details)
            $table->json('properties')->nullable();

            // Optional: IP and User-Agent info
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('log_name');
            $table->index('event');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = config('activitylog.table_name', 'activity_log');
        $connection = config('activitylog.database_connection', config('database.default'));

        Schema::connection($connection)->dropIfExists($tableName);
    }
}
