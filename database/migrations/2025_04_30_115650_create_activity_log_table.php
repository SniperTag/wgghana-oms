<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = config('activitylog.table_name', 'activity_log'); // Default to 'activity_log' if not set

        Schema::connection(config('activitylog.database_connection', 'mysql')) // Default to 'mysql' if not set
              ->create($tableName, function (Blueprint $table) {
                  $table->bigIncrements('id');
                  $table->string('log_name')->nullable();
                  $table->text('description');
                  $table->nullableMorphs('subject'); // Correct morph column without prefix
                  $table->nullableMorphs('causer'); // Correct morph column without prefix
                  $table->json('properties')->nullable();
                  $table->timestamps();
                  $table->index('log_name');
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
              ->dropIfExists($tableName);
    }
}
