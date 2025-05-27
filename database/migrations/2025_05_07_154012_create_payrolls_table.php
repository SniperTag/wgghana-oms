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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys for tax and SSNIT
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->unsignedBigInteger('ssnit_id')->nullable();
            
            // Foreign key constraints
            $table->foreign('tax_id')
                ->references('id')->on('taxes')
                ->onDelete('set null');
            
            $table->foreign('ssnit_id')
                ->references('id')->on('ssnits')
                ->onDelete('set null');
            
            // Foreign key for staff_id (users table)
            $table->foreignId('staff_id')
                ->constrained('users')
                ->onDelete('cascade');
            
            // Payroll fields
            $table->string('month'); // e.g., '2025-05'
            $table->decimal('gross_salary', 10, 2);
            $table->decimal('net_salary', 10, 2);
            $table->decimal('overtime', 10, 2)->nullable();
            $table->enum('payment_method', ['Bank', 'Momo', 'TableTop'])->nullable();
            $table->text('notes')->nullable();
            $table->string('payslip_path')->nullable();
            $table->string('currency')->default('USD');
            $table->date('payment_date')->nullable();
            $table->string('payment_reference')->nullable();
            
            // Bank Details
            $table->string('bank_account')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_swift_code')->nullable();
            $table->string('bank_iban')->nullable();
            $table->string('bank_account_holder')->nullable();
            $table->string('bank_account_type')->nullable();
            
            // Payment Gateway Info
            $table->string('payment_gateway')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_status')->default('pending');
        
            $table->timestamps();
        
            // Unique constraint for staff_id and month to prevent duplicates
            $table->unique(['staff_id', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
