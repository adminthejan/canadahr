<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeContribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'basic_salary',
        'epf_number',
        'etf_number',
        'total_epf_contributed',
        'total_etf_contributed',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
