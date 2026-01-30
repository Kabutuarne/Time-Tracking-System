<?php

namespace App\Models;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entry extends Model
{
    /** @use HasFactory<\Database\Factories\EntryFactory> */
    use HasFactory;
    protected $fillable = [
        'task_id',
        'user_id',
        'title',
        'work_date',
        'minutes',
        'description',
    ];

    public function task(){
        return $this->belongsTo(Task::class);
    }
    //created by the user
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function project()
{
    return $this->hasOneThrough(
        Project::class,
        Task::class,
        'id',          // Task primary key
        'id',          // Project primary key
        'task_id',     // Entry -> task
        'project_id'   // Task -> project
        );
    }
}
