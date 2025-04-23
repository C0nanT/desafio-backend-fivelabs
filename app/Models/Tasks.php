<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'created_by',
        'priority',
        'responsible',
    ];
    
    protected $casts = [
        'due_date' => 'date'
    ];
}
