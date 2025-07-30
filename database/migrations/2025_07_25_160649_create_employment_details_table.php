<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Linked to User
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->string('job_title')->nullable();
            $table->enum('employment_type', ['fulltime', 'parttime', 'contract', 'intern'])->default('fulltime');
            $table->date('start_date')->nullable(); // Start date of employment
            $table->date('end_date')->nullable(); // End date of employment, if applicable
            $table->string('pay_grade')->nullable(); // e.g., A1, B2, etc.
            $table->decimal('salary', 10, 2)->nullable(); // Monthly salary
            $table->text('benefits')->nullable(); // Benefits description
            $table->string('contract_duration')->nullable(); // e.g., 6 months, 1 year, etc.
            $table->enum('employment_status', ['active', 'inactive', 'terminated'])->default('active');
            // User Type: employee, national_service, intern, consultant
            $table->enum('user_type', ['employee', 'national_service', 'intern', 'consultant'])->default('employee');
            $table->date('date_of_joining')->nullable();
            $table->string('work_location')->nullable(); // e.g., Office, Remote, Hybrid
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null'); // Supervisor
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_details');
    }
};
