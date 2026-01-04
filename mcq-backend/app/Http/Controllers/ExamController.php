<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;

class ExamController extends Controller
{
    public function getExamList()
    {
        return response()->json(
            Exam::select('id', 'title')->get()
        );
    }
}
