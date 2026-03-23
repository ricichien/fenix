<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttemptControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_submit_attempt_successfully()
    {
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

        $examData = $this->getJson("/api/exams/{$exam['id']}")->json();

        $questionId = $examData['questions'][0]['id'];
        $optionId = $examData['questions'][0]['options'][0]['id'];

        $payload = [
            'student_id' => 1,
            'answers' => [
                [
                    'question_id' => $questionId,
                    'selected_option_id' => $optionId
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
    public function test_submit_wrong_answer()
    {
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

        $examResponse = $this->getJson("/api/exams/{$exam['id']}");
        $questionId = $examResponse->json('questions.0.id');

        // opção errada (provavelmente a segunda)
        $optionId = $examResponse->json('questions.0.options.1.id');

        $response = $this->postJson("/api/exams/{$exam['id']}/submit", [
            'student_id' => 1,
            'answers' => [
                [
                    'question_id' => $questionId,
                    'selected_option_id' => $optionId
                ]
            ]
        ]);

        $response->assertStatus(200)
                ->assertJsonPath('data.score', 0);
    }
    public function test_store_question_validation_fails()
    {
        $response = $this->postJson('/api/questions', []);

        $response->assertStatus(422);
    }
}