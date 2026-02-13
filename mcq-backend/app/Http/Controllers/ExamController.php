<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    
    public function index()
    {
        $exams = Exam::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $exams
        ], 200);
    }
    //get exam list for dropdown
    // public function getExamlist()
    // {
    //     $exams = Exam::select('id', 'title')->orderBy('created_at', 'desc')->get();
        
    //     return response()->json([
    //         'success' => true,
    //         'data' => $exams
    //     ], 200);
    // }

    /**
     * Store a newly created exam in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_marks' => 'required|integer|min:1',
            'pass_marks' => 'required|integer|lte:total_marks',
            'duration_minutes' => 'required|integer|min:1',
            'is_published' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // ডাটাবেসে সেভ করা
        $exam = Exam::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Exam created successfully!',
            'data' => $exam
        ], 201);
    }

    /**
     * Display the specified exam.
     */
    public function show($id)
    {
        $exam = Exam::find($id);

        if (!$exam) {
            return response()->json(['message' => 'Exam not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $exam], 200);
    }

    /**
     * Update the specified exam in storage.
     */
    public function update(Request $request, $id)
    {
        $exam = Exam::find($id);

        if (!$exam) {
            return response()->json(['message' => 'Exam not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'total_marks' => 'integer|min:1',
            'pass_marks' => 'integer|lte:total_marks',
            'duration_minutes' => 'integer|min:1',
            'is_published' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $exam->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Exam updated successfully!',
            'data' => $exam
        ], 200);
    }

    /**
     * Remove the specified exam from storage.
     */
    public function destroy($id)
    {
        $exam = Exam::find($id);

        if (!$exam) {
            return response()->json(['message' => 'Exam not found'], 404);
        }

        $exam->delete();

        return response()->json([
            'success' => true,
            'message' => 'Exam deleted successfully!'
        ], 200);
    }
}
