<?php

namespace App\Models;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'status',
        'is_public'
    ];
    public function user()
        {
            return $this->belongsTo(User::class);
        }

    public function users()
        {
            return $this->belongsToMany(User::class)
                ->withPivot('role')
                ->withTimestamps();
        }

    public function tasks()
        {
            return $this->hasMany(Task::class);
        }
    public function entries()
        {
            return $this->hasManyThrough(Entry::class, Task::class, 'project_id', 'task_id');
        }
}