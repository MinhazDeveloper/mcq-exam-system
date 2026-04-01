<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GeminiTest extends TestCase
{
    public function test_api_generates_mcqs_without_hitting_google()
    {
        // ১. জেমিনি এপিআই-কে ফেক (Fake) করা
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => json_encode([
                                    [
                                        "question" => "ঈমানের শাখা কয়টি?",
                                        "options" => ["৭০ এর অধিক", "৬০টি", "৫০টি", "৪০টি"],
                                        "correct_answer" => "৭০ এর অধিক"
                                    ]
                                ])]
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        // ২. আপনার এপিআই এন্ডপয়েন্টে রিকোয়েস্ট পাঠানো
        $response = $this->postJson('/api/instructor/questions/generate-from-ai', [
            'file' => UploadedFile::fake()->image('test.jpg'),
            'count' => 1
        ]);

        // ৩. চেক করা যে রেসপন্স ঠিক আছে কি না
        $response->assertStatus(200);
        $response->assertJsonFragment(['question' => 'ঈমানের শাখা কয়টি?']);
    }
}