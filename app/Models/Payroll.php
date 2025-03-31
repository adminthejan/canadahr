<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payroll_month',
        'basic_salary',
        'net_salary',
        'payable',
        'pay_date',
        'total_hours',
        'tax',
        'status'
    ];


    /**
     * Relationship: Employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

        /**
     * Get the allowances associated with the payroll.
     */
    public function allowances()
    {
        return $this->hasMany(Allowance::class);
    }

    /**
     * Get the deductions associated with the payroll.
     */
    public function deductions()
    {
        return $this->hasMany(Deduction::class);
    }
}
