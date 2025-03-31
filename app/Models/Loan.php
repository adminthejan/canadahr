<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'employee_name',
        'employment_ID',
        'loan_amount',
        'monthly_paid',
        'remaining_balance',
        'loan_start_date',
        'duration',
        'loan_end_date',
        'interest_rate',
        'status',
        'advance_documents',
        'description',

    ];

    /**
     * Relationship: Employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
