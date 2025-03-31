<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('employee_name');
            $table->string('employment_ID');
            $table->decimal('loan_amount', 10, 2);
            $table->decimal('monthly_paid', 10, 2)->default(0.00);
            $table->decimal('remaining_balance', 10, 2)->nullable();
            $table->date('loan_start_date');
            $table->integer('duration');
            $table->date('loan_end_date')->nullable();
            $table->decimal('interest_rate', 5, 2)->nullable()->comment('Annual interest rate in percentage');
            $table->enum('status', ['approved', 'pending', 'rejected'])->default('approved');
            $table->json('advance_documents')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('loans');
    }
};
