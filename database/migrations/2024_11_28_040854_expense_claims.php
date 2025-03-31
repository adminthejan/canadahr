<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expense_claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('category'); // New category column
            $table->json('supporting_documents')->nullable(); // New column to store multiple document paths
            $table->text('description')->nullable();
            $table->text('approved_by')->nullable();
            $table->decimal('amount', 10, 2);
            $table->date('submitted_date');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('expense_claims');
    }
};
