<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $table = 'leaves';
    protected $fillable = [
        'employee_id',
        'employee_name',
        'employment_ID',
        'leave_type',
        'start_date',
        'end_date',
        'duration',
        'approved_person',
        'status',
        'supporting_documents',
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
