<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_question()
    {
        $examResponse = $this->postJson('/api/exams', [
            'title' => 'Exam Test',
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
        ]);

        $examId = $examResponse->json('id');

        $payload = [
            'exam_id' => $examId,
            'title' => 'Question 1',
            'statement' => 'Qual é a resposta?',
            'options' => [
                ['text' => 'A', 'is_correct' => true],
                ['text' => 'B', 'is_correct' => false],
            ],
        ];

        $response = $this->postJson('/api/questions', $payload);

        $response->assertStatus(201)
         ->assertJsonFragment([
             'statement' => 'Qual é a resposta?'
         ]);
    }
}