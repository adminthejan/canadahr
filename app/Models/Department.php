<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model 
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'name',
        'branch',
    ];

    /**
     * Get the employees in this department
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'department_id');
    }

    public function getEmployeesCountAttribute()
    {
        return $this->employees()->count();
    }
    
}