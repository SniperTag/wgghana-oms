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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('company')->nullable();
            $table->text('purpose_of_visit')->nullable();
            $table->timestamp('visit_date')->useCurrent();
            $table->timestamp('check_in_time')->nullable();
            $table->timestamp('check_out_time')->nullable();
            $table->foreignId('host_id')->nullable()->constrained('users')->onDelete('set null'); // Foreign key to Users table (host)
            $table->enum('status', ['pending', 'approved', 'checked_in', 'checked_out'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
