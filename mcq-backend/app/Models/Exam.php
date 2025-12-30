<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\Submission;


class Exam extends Model
{
    protected $fillable = ['title', 'description', 'total_marks', 'pass_marks', 'duration_minutes', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
        'total_marks' => 'integer',
        'pass_marks' => 'integer',
        'duration_minutes' => 'integer',
    ];
    
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

}
