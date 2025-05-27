<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventColumnToActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Define the table name here
        $tableName = config('activitylog.table_name', 'activity_log'); // Default to 'activity_log' if not set

        Schema::connection(config('activitylog.database_connection', 'mysql')) // Default to 'mysql' if not set
            ->table($tableName, function (Blueprint $table) use ($tableName) {  // Use the $tableName variable here
                // Make sure 'subject_type' column exists before adding the 'event' column after it
                if (Schema::hasColumn($tableName, 'subject_type')) {
                    $table->string('event')->nullable()->after('subject_type');
                } else {
                    // If 'subject_type' does not exist, just add the column at the end
                    $table->string('event')->nullable();
                }
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableName = config('activitylog.table_name', 'activity_log'); // Default to 'activity_log' if not set

        Schema::connection(config('activitylog.database_connection', 'mysql')) // Default to 'mysql' if not set
            ->table($tableName, function (Blueprint $table) {
                $table->dropColumn('event');
            });
    }
}
