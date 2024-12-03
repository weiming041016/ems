<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLeaveRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_leave_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('message')->nullable();
            $table->enum('type_of_leave', ['annual', 'mc', 'hospitalization', 'maternity','paternity', 'unpaid']);
            $table->date('from');
            $table->date('to');
            $table->string('attach')->nullable();
            $table->enum('status', ['WAITING_FOR_APPROVAL', 'APPROVED', 'REJECTED'])->default('WAITING_FOR_APPROVAL');
            $table->text('reject_reason')->nullable();
            $table->string('comment')->nullable();
            $table->unsignedBigInteger('checked_by')->nullable();
            $table->timestamps();
        
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('checked_by')->references('id')->on('employees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_leave_requests');
    }
}
