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
        'name',
        'description',
        'user_id',
        'status',
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

}
