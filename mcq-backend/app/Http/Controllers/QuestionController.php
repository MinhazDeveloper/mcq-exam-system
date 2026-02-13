<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreQuestionRequest;

class QuestionController extends Controller
{
     public function index() {
        $questions = Question::with(['options', 'exam'])->orderBy('created_at', 'desc')->get();
        return response()->json(['success' => true, 'data' => $questions]);
    }

    public function store(StoreQuestionRequest $request) {
        try {
            // DB Transaction
            return DB::transaction(function () use ($request) {
                
                $question = Question::create([
                    'exam_id' => $request->exam_id,
                    'question_text' => $request->question_text,
                    'mark' => $request->mark ?? 1,
                    'explanation' => $request->explanation,
                ]);
                
                foreach ($request->options as $opt) {
                    $question->options()->create([
                        'option_text' => $opt['text'],
                        'is_correct' => $opt['is_correct']
                    ]);
                }

                return response()->json([
                    'message' => 'Question and options created successfully!',
                    'data' => $question->load('options')
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }
    public function destroy($id) {
        $question = Question::findOrFail($id);
        $question->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
    
    public function update(StoreQuestionRequest $request, $id) 
    {
        $question = Question::find($id);

        if (!$question) {
            return response()->json(['success' => false, 'message' => 'Question not found'], 404);
        }

        try {
            return DB::transaction(function () use ($request, $question) {
                
                $question->update([
                    'exam_id'       => $request->exam_id,
                    'question_text' => $request->question_text,
                    'mark'          => $request->mark,
                    'explanation'   => $request->explanation,
                ]);

                
                $question->options()->delete();

                foreach ($request->options as $opt) {
                    $question->options()->create([
                        'option_text' => $opt['text'],
                        'is_correct'  => $opt['is_correct']
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Question and Options updated successfully!',
                    'data'    => $question->load('options')
                ], 200);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error'   => $e->getMessage()
            ], 500);
        }
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
