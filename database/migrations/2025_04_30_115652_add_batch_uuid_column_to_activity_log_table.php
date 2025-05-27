<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBatchUuidColumnToActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Define the table name from the config file, with a default fallback value
        $tableName = config('activitylog.table_name', 'activity_log'); // Default to 'activity_log' if not set

        // Apply the changes to the specified database connection
        Schema::connection(config('activitylog.database_connection', 'mysql')) // Default to 'mysql' if not set
            ->table($tableName, function (Blueprint $table) {
                // Add the batch_uuid column after the properties column
                $table->uuid('batch_uuid')->nullable()->after('properties');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Define the table name from the config file
        $tableName = config('activitylog.table_name', 'activity_log'); // Default to 'activity_log' if not set

        // Apply the reverse changes
        Schema::connection(config('activitylog.database_connection', 'mysql')) // Default to 'mysql' if not set
            ->table($tableName, function (Blueprint $table) {
                // Drop the batch_uuid column if it exists
                $table->dropColumn('batch_uuid');
            });
    }
}
