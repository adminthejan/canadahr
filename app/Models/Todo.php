<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = ['user_id', 'title', 'due_date', 'priority', 'status'];
    protected $casts = [
        'due_date' => 'datetime',
    ];
}
