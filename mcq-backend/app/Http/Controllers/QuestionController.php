<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreQuestionRequest;

class QuestionController extends Controller
{
    public function index() {

        return Question::with('options')->latest()->get();
    }

    public function store(StoreQuestionRequest $request) {
        try {
            // DB Transaction
            return DB::transaction(function () use ($request) {
                
                $question = Question::create([
                    'exam_id' => $request->exam_id,
                    'question_text' => $request->question_text,
                    'mark' => $request->mark ?? 1,
                    
                ]);

                $question->options()->createMany($request->options);

                return response()->json([
                    'message' => 'Question and options created successfully!',
                    'data' => $question->load('options')
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }
    
    public function show($id)
    {
        $question = Question::with('options')->find($id);
        
        if (!$question) {
            return response()->json(['message' => 'Question not found'], 404);
        }
        
        return response()->json($question);
    }
    public function update(Request $request, $id) {
        $question = Question::findOrFail($id);
        
        DB::transaction(function () use ($request, $question) {
            $question->update([
                'exam_id' => $request->exam_id,
                'question_text' => $request->question_text,
                'mark' => $request->mark
            ]);

            $question->options()->delete();
            $question->options()->createMany($request->options);
        });

        return response()->json(['message' => 'Updated successfully']);
    }
    public function destroy($id) {
        $question = Question::find($id);

        if (!$question) {
            return response()->json(['message' => 'Question not found'], 404);
        }

        $question->options()->delete(); 
        $question->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
    //get question for student
    public function getQuestionsForStudent($examId)
    {
        $questions = Question::with(['options' => function($query) {
            $query->select('id', 'question_id', 'option_text', 'is_correct'); 
        }])
        ->where('exam_id', $examId)
        ->get();

        return response()->json($questions);
    }
}
