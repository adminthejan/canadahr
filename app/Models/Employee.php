<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'age',
        'nic',
        'gender',
        'title',
        'employment_type',
        'image',
        'current',
        'legal_documents',
        'employee_id',
        'education_id',
        'description',
        'probation_start_date',
        'probation_period',
        'department_id',
        'manager_id',
        'employment_start_date',
        'employment_end_date',
        'account_holder_name',
        'bank_name',
        'account_no',
        'branch_name',
    ];

   

    /**
     * Relationship: Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }


    /**
     * Relationship: Manager
     */
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    /**
     * Relationship: Subordinates
     */
    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }
    
    public function education()
    {
        return $this->belongsTo(Education::class, 'education_id', 'id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'department_id', 'department_id');
    }
}

