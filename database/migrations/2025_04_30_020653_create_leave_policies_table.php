<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavePoliciesTable extends Migration
{
    public function up()
    {
        Schema::create('leave_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('leave_type_id')->nullable(); // Must match `leave_types.id`

            $table->integer('total_days');
            $table->integer('year')->default(date('Y'));
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('leave_policies');
    }
}
