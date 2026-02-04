<?php

namespace App\Models;

use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;
    
    // protected $fillable = [
    //     'title',
    //     'description',
    //     'project_id',
    //     'due_date',
    //     'completed_at',
    //     'status',
    // ];
    protected $guarded = [];

    // lets me use isToday and isPast methods on due_date and completed_at
    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function project(){
        return $this->belongsTo(Project::class);
    }
    public function entries(){
        return $this->hasMany(Entry::class);
    }
    public function completedBy(){ //returns the user who marked the task as completed
        return $this->belongsTo(User::class, 'completed_by');
    }
    public function markComplete(int $userId): void
    {
        $this->completed_at = now();
        $this->completed_by = $userId;
        $this->status = 'completed';
        $this->save();
    }

}
