<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttemptControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_submit_attempt_successfully()
    {
        // cria exame real
        $exam = $this->postJson('/api/exams', [
            'title' => 'Exam',
            'description' => 'Desc',
            'questions' => [
                [
                    'statement' => '2 + 2 = ?',
                    'options' => [
                        ['text' => '4', 'is_correct' => true],
                        ['text' => '5', 'is_correct' => false],
                    ],
                ],
            ],
        ])->json();

        $payload = [
            'student_id' => 1,
            'answers' => [
                [
                    'question_id' => 1,
                    'selected_option_id' => 1
                ]
            ]
        ];

        $response = $this->postJson("/api/exams/{$exam['id']}/submit", $payload);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'data' => ['score']
                 ]);
    }

    public function test_submit_validation_fails()
    {
        $examId = 1;

        $payload = [
            'student_id' => null
        ];

        $response = $this->postJson("/api/exams/{$examId}/submit", $payload);

        $response->assertStatus(422);
    }
}