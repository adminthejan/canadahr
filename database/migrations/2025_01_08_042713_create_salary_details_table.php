<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_salary_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id'); // Foreign key to the employee table
            $table->string('employee_name');
            $table->string('known_name');
            $table->string('epf_no');
            $table->float('basic', 10, 2);
            $table->float('budget_allowance', 10, 2);
            $table->float('gross_salary', 10, 2);
            $table->float('transport_allowance', 10, 2)->nullable();
            $table->float('attendance_allowance', 10, 2)->nullable();
            $table->float('phone_allowance', 10, 2)->nullable();
            $table->float('production_bonus', 10, 2)->nullable();
            $table->float('car_allowance', 10, 2)->nullable();
            $table->float('loan_payment', 10, 2)->nullable();
            $table->float('ot_payment', 10, 2)->nullable();
            $table->float('total_earnings', 10, 2);
            $table->float('epf_8_percent', 10, 2)->nullable();
            $table->float('epf_12_percent', 10, 2)->nullable();
            $table->float('etf_3_percent', 10, 2)->nullable();
            $table->float('advance_payment', 10, 2)->nullable();
            $table->float('stamp_duty', 10, 2)->nullable();
            $table->float('no_pay', 10, 2)->nullable();
            $table->float('total_deductions', 10, 2);
            $table->float('net_salary', 10, 2);
            $table->float('loan_balance', 10, 2)->nullable();
            $table->date('pay_date'); // New field for the date of payment
            $table->string('payed_month'); // New field for the month being paid for


            // Foreign key constraint
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_salary_details');
    }
}
