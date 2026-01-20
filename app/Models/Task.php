<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'project_id',
        'due_date',
        'completed_at',
        'status',
    ];
    public function project(){
        return $this->belongsTo(Project::class);
    }
    public function entries(){
        return $this->hasMany(Entry::class);
    }
}
