<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'category', // New column
        'supporting_documents', // New column
        'description',
        'amount',
        'submitted_date',
        'status',
    ];

    /**
     * Relationship: Employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**php artisan migrate:rollback --path=/database/migrations/2024_11_28_040854_expense_claims.php
     * php artisan migrate --path=/database/migrations/2024_11_28_040854_expense_claims.php
     * Accessor for supporting documents
     */
}
