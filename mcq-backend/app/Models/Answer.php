<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Submission;
use App\Models\Question;
use App\Models\Option;

class Answer extends Model
{
    protected $fillable = ['submission_id', 'question_id', 'selected_option_id', 'is_correct'];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
    
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function selectedOption()
    {
        return $this->belongsTo(Option::class, 'selected_option_id');
    }

}
