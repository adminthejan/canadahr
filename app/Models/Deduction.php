<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_id',
        'description',
        'amount'
    ];

    /**
     * Get the payroll that owns the deduction.
     */
    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}
