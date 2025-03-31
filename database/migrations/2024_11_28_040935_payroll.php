<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('payroll_month');
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('net_salary', 10, 2);
            $table->decimal('payable', 10, 2);
            $table->date('pay_date');
            $table->integer('total_hours')->nullable();
            $table->decimal('tax', 10, 2)->nullable();
            $table->decimal('bonus', 10, 2)->nullable();
            $table->string('status');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        Schema::create('allowances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payroll_id');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->timestamps();

            $table->foreign('payroll_id')->references('id')->on('payrolls')->onDelete('cascade');
        });

        Schema::create('deductions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payroll_id');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->timestamps();

            $table->foreign('payroll_id')->references('id')->on('payrolls')->onDelete('cascade');
        });
    }
        public function down()
        {
            Schema::dropIfExists('deductions');
            Schema::dropIfExists('allowances');
            Schema::dropIfExists('payrolls');
        }
};
