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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('serial_number')->unique();
            $table->string('model')->nullable();
            $table->string('brand')->nullable();
            $table->string('category')->nullable();
            //asset_type
            $table->enum('asset_type', ['hardware', 'software', 'furniture', 'vehicle'])->default('hardware');
            //asset_subtype
            $table->enum('asset_subtype', ['laptop', 'desktop', 'printer', 'monitor', 'network_device', 'software_license'])->default('laptop');
            //asset_tag
            $table->string('asset_tag')->nullable();
            //purchase_order_number
            $table->string('purchase_order_number')->nullable();
            //vendor
            $table->string('vendor')->nullable();
            //vendor_contact
            $table->string('vendor_contact')->nullable();
            //vendor_phone
            $table->string('vendor_phone')->nullable();
            //vendor_email
            $table->string('vendor_email')->nullable();
            //condition
            $table->enum('condition', ['new', 'used', 'refurbished'])->default('new');
            //status
            $table->enum('status', ['available', 'in_use', 'maintenance', 'retired'])->default('available');
            //location
            $table->unsignedBigInteger('location_id');  // Ensure it's unsigned
            // $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            //assigned_to
            $table->foreignId('assigned_to_id')->nullable()->constrained('users')->onDelete('set null');
            //purchase_date
            $table->date('purchase_date')->nullable();
            //warranty_period
            $table->integer('warranty_period')->nullable(); // in months
            //warranty_expiry_date
            $table->date('warranty_expiry_date')->nullable();
            //purchase_price
            $table->decimal('purchase_price', 10, 2)->nullable();
            //current_value
            $table->decimal('current_value', 10, 2)->nullable();
            //image
            $table->string('image')->nullable();
            //notes
            $table->text('notes')->nullable();
            //deleted_at
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
