<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employee_contributions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->decimal('basic_salary', 15, 2)->default(0); // Employee's basic salary
            $table->string('epf_number')->nullable();           // EPF number for the employee
            $table->string('etf_number')->nullable();           // ETF number for the employee
            $table->decimal('total_epf_contributed', 15, 2)->default(0); // Total EPF contribution
            $table->decimal('total_etf_contributed', 15, 2)->default(0); // Total ETF contribution
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_contributions');
    }
};
