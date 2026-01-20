<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    /** @use HasFactory<\Database\Factories\EntryFactory> */
    use HasFactory;
    protected $fillable = [
        'task_id',
        'user_id',
        'title',
        'description',
    ];

    public function task(){
        return $this->belongsTo(Task::class);
    }
}
