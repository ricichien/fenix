<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_returns_data()
    {
        $response = $this->getJson('/api/dashboard');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'total_attempts',
                     'average_score',
                     'average_percentage',
                     'highest_score',
                     'lowest_score',
                     'ranking',
                 ]);
    }

    public function test_exam_stats()
    {
        $payload = [
            'title' => 'Exam Stats',
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

        $response = $this->getJson("/api/exams/{$exam['id']}/stats");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'average_score',
                     'average_percentage',
                 ]);
    }
}