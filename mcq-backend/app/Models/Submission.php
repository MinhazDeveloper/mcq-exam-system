<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Exam;
use App\Models\Answer;

class Submission extends Model
{
    protected $fillable = ['user_id', 'exam_id', 'obtained_marks', 'time_taken'];

    protected $casts = [
        'obtained_marks' => 'integer',
        'time_taken' => 'integer', // if storing time in minutes or seconds
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    // 1 submission has many answer
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

}
