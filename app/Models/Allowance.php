<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Allowance extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_id',
        'description',
        'amount'
    ];

    /**
     * Get the payroll that owns the allowance.
     */
    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}
