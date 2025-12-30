<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Exam;
use App\Models\Option;


class Question extends Model
{
    protected $fillable = ['exam_id', 'question_text', 'image', 'mark', 'explanation'];

    protected $casts = [
        'mark' => 'integer',
    ];

    public function exam(){

        return $this->belongsTo(Exam::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

}
