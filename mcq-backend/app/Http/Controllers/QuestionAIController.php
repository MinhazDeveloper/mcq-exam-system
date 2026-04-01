<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Option;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class QuestionAIController extends Controller
{
    // question generation from media (image/pdf) using Gemini API
    public function generateFromMedia(Request $request, GeminiService $gemini)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
            'count' => 'required|integer|min:1|max:50'
        ]);

        try {
            $response = $gemini->generateQuestions($request->file('file'), $request->input('count', 10));

            if ($response->failed()) {
                Log::error("Gemini API Error: " . $response->body());
                return response()->json(['error' => 'AI service unavailable'], 502);
            }

            $result = $response->json();
            // json decode check and extract questions
            $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
            $questions = json_decode($text, true);

            return response()->json(['status' => 'success', 'data' => $questions]);

        } catch (\Exception $e) {
            Log::error("AI System Failure: " . $e->getMessage());
            return response()->json(['error' => 'Server encountered an error'], 500);
        }
    }
    // bulk store questions save to database
    public function bulkStore(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'questions' => 'required|array|min:1',
            'exam_id' => 'required|integer', // which exam these questions belong to
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $questionsData = $request->input('questions');
                $examId = $request->input('exam_id');
                $savedCount = 0;

                foreach ($questionsData as $data) {
                    $question = Question::create([
                        'exam_id'       => $examId,
                        'question_text' => $data['question'],
                        'mark'          => 1,
                        'explanation'   => $data['explanation'] ?? null,
                        'user_id'       => auth()->id(),
                    ]);

                    $optionsToInsert = [];
                    foreach ($data['options'] as $index => $optionText) {
                        $correctVal = is_array($data['correct_answer']) ? ($data['correct_answer'][0] ?? '') : $data['correct_answer'];

                        $optionsToInsert[] = [
                            'question_id' => $question->id,
                            'option_text' => $optionText,
                            'is_correct'  => trim((string)$optionText) === trim((string)$correctVal), 
                            'created_at'  => now(),
                            'updated_at'  => now(),
                        ];
                    }

                    Option::insert($optionsToInsert);
                    $savedCount++;
                }

                return response()->json([
                    'status' => 'success',
                    'message' => "{$savedCount} questions saved successfully."
                ]);
            });
        } catch (\Exception $e) {
            Log::error("Bulk Store Error: " . $e->getMessage());
            return response()->json(['error' => 'Error saving questions: ' . $e->getMessage()], 500);
        }
    }
}