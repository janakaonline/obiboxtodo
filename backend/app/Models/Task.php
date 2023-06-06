<?php

namespace App\Models;

use App\Enums\TaskPriority;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'name',
        'priority',
        'description',
        'due_date',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'priority' => TaskPriority::class,
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
    ];
}
