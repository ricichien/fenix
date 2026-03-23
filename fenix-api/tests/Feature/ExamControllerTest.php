<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExamControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_exams()
    {
        $payload = [
            'title' => 'Exam 1',
            'description' => 'Desc',
            'questions' => [
                [
                    'statement' => 'Q1',
                    'options' => [
                        ['text' => 'A', 'is_correct' => true],
                        ['text' => 'B', 'is_correct' => false],
                    ],
                ],
            ],
        ];

        $this->postJson('/api/exams', $payload);

        $response = $this->getJson('/api/exams');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    public function test_store_exam()
    {
        $payload = [
            'title' => 'New Exam',
            'description' => 'Test exam',
            'questions' => [
                [
                    'statement' => '2 + 2 = ?',
                    'options' => [
                        ['text' => '4', 'is_correct' => true],
                        ['text' => '5', 'is_correct' => false],
                    ],
                ],
            ],
        ];

        $response = $this->postJson('/api/exams', $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'New Exam']);
    }

    public function test_show_exam()
    {
        $payload = [
            'title' => 'Exam Show',
            'description' => 'Desc',
            'questions' => [
                [
                    'statement' => 'Q1',
                    'options' => [
                        ['text' => 'A', 'is_correct' => true],
                        ['text' => 'B', 'is_correct' => false],
                    ],
                ],
            ],
        ];

        $exam = $this->postJson('/api/exams', $payload)->json();

        $response = $this->getJson("/api/exams/{$exam['id']}");

        $response->assertStatus(200);
    }
    public function test_exam_update_validation_fails()
    {
        $response = $this->putJson('/api/exams/1', []);

        $response->assertStatus(422);
    }
    public function test_delete_exam()
    {
        $exam = $this->postJson('/api/exams', [
            'title' => 'Delete Test',
            'description' => 'Desc',
            'questions' => [
                [
                    'statement' => 'Q1',
                    'options' => [
                        ['text' => 'A', 'is_correct' => true],
                        ['text' => 'B', 'is_correct' => false],
                    ],
                ],
            ],
        ])->json();

        $response = $this->deleteJson("/api/exams/{$exam['id']}");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Exam deleted successfully'
                ]);
    }
    public function test_show_with_answers()
    {
        $exam = $this->postJson('/api/exams', [
            'title' => 'Exam',
            'description' => 'Desc',
            'questions' => [
                [
                    'statement' => 'Q1',
                    'options' => [
                        ['text' => 'A', 'is_correct' => true],
                        ['text' => 'B', 'is_correct' => false],
                    ],
                ],
            ],
        ])->json();

        $response = $this->getJson("/api/exams/{$exam['id']}/edit");

        $response->assertStatus(200);
    }
    public function test_dashboard_empty_state()
    {
        $response = $this->getJson('/api/dashboard');

        $response->assertStatus(200)
                ->assertJson([
                    'total_attempts' => 0
                ]);
    }
    public function test_exam_update_success()
    {
        $exam = $this->postJson('/api/exams', [
            'title' => 'Old',
            'description' => 'Desc',
            'questions' => [
                [
                    'statement' => 'Q1',
                    'options' => [
                        ['text' => 'A', 'is_correct' => true],
                        ['text' => 'B', 'is_correct' => false],
                    ],
                ],
            ],
        ])->json();

        $response = $this->putJson("/api/exams/{$exam['id']}", [
            'title' => 'Updated',
            'description' => 'Updated desc',
            'questions' => [
                [
                    'statement' => 'Q1 updated',
                    'options' => [
                        ['text' => 'A', 'is_correct' => true],
                        ['text' => 'B', 'is_correct' => false],
                    ],
                ],
            ],
        ]);

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'title' => 'Updated'
                ]);
    }
    public function test_attempt_fails_validation()
    {
        $response = $this->postJson('/api/exams/1/submit', []);

        $response->assertStatus(422);
    }
}