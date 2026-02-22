<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Submission;

class InstructorController extends Controller
{
    public function dashboard()
    {
        return response()->json([
            'success' => true,
            'message' => 'Welcome Instructor',
        ]);
    }
    public function getStats() {
        $id = auth()->id();
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_exams' => Exam::where('user_id', $id)->count(),
                'active_students' => Submission::whereIn('exam_id', function($query) use ($id){
                                        $query->select('id')->from('exams')->where('user_id', $id);
                                    })->distinct('user_id')->count(),
               
            ]
        ]);
    }
}
