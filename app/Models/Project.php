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
        'owner_id',
        'status',
    ];
    public function users(){
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }
    public function tasks(){
        return $this->hasMany(Task::class);
    }
}
