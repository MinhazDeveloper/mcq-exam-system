<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Exception;

class GeminiService {
    public function generateQuestions(UploadedFile $file, int $count) {

        // FAKE API TEST START
        // if (config('app.env') === 'local' && config('services.gemini.key') === 'mock') {
        //     $mockQuestions = [];
        //     for ($i = 1; $i <= $count; $i++) {
        //         $mockQuestions[] = [
        //             "question" => "Sample Question $i from AI?",
        //             "options" => ["Option A", "Option B", "Option C", "Option D"],
        //             "correct_answer" => "Option A"
        //         ];
        //     }

        //     return new \Illuminate\Http\Client\Response(
        //         new \GuzzleHttp\Psr7\Response(200, [], json_encode([
        //             'candidates' => [['content' => ['parts' => [['text' => json_encode($mockQuestions)]]]]]
        //         ]))
        //     );
        // }
        // FAKE API TEST END
  
        $apiKey = config('services.gemini.key');
        if (!$apiKey) {
            throw new Exception('Gemini API key is missing in configuration.');
        }
        // $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

        $prompt = "You are an expert educator. Analyze the attached document or image carefully. 
                    Generate EXACTLY {$count} unique multiple-choice questions (MCQs) based on the content.
                    Output must be a valid JSON array of objects. 
                    Each object must follow this exact schema:
                    {
                        \"question\": \"string\",
                        \"options\": [\"string\", \"string\", \"string\", \"string\"],
                        \"correct_answer\": \"string (must match one of the options exactly)\"
                    }";

        try {
            // Read file content and encode as base64 for inline data
            $rawContent = file_get_contents($file->getRealPath());
            $base64Data = base64_encode($rawContent);

            $response = Http::timeout(120)->post($apiUrl, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inline_data' => [
                                    'mime_type' => $file->getMimeType(),
                                    'data' => $base64Data
                                ]
                            ]
                        ]
                    ]
                ],
                
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                    'temperature' => 1,
                    'maxOutputTokens' => 8192,
                ]
                
            ]);
            
            if ($response->failed()) {
                Log::error("Gemini API Error: " . $response->body());
                throw new Exception("Gemini API failed with status: " . $response->status());
            }
            
            return $response;

            } catch (Exception $e) {
                Log::error("Gemini Service Exception: " . $e->getMessage());
                throw $e; 
            }

    }
}
