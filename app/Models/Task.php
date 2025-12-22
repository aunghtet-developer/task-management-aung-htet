<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the status options.
     *
     * @return array<string, string>
     */
    public static function getStatusOptions(): array
    {
        return [
            'todo' => 'Todo',
            'in_progress' => 'In Progress',
            'done' => 'Done',
        ];
    }

    /**
     * Get the priority options.
     *
     * @return array<string, string>
     */
    public static function getPriorityOptions(): array
    {
        return [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
        ];
    }
}
