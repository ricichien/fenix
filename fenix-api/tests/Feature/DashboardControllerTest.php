<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_returns_data_with_total_questions(): void
    {
        $exam = Exam::create([
            'title' => 'Exam 1',
            'created_by' => 1,
        ]);

        Question::create([
            'exam_id' => $exam->id,
            'statement' => 'Q1',
        ]);

        Attempt::create([
            'exam_id' => $exam->id,
            'student_id' => 1,
            'score' => 1,
            'percentage' => 100,
        ]);

        $response = $this->getJson('/api/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'total_attempts',
                'total_questions',
                'average_score',
                'average_percentage',
                'highest_score',
                'lowest_score',
                'ranking',
            ]);

        $response->assertJsonPath('total_attempts', 1);
        $response->assertJsonPath('total_questions', 1);
    }

    public function test_dashboard_paginates_ranking(): void
    {
        $exam = Exam::create([
            'title' => 'Exam 1',
            'created_by' => 1,
        ]);

        Question::create([
            'exam_id' => $exam->id,
            'statement' => 'Q1',
        ]);

        for ($i = 1; $i <= 12; $i++) {
            Attempt::create([
                'exam_id' => $exam->id,
                'student_id' => $i,
                'score' => $i,
                'percentage' => $i * 10,
            ]);
        }

        $response = $this->getJson('/api/dashboard?page=1&limit=5');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'ranking');
    }

    public function test_exam_stats_returns_404_for_missing_exam(): void
    {
        $response = $this->getJson('/api/exams/999999/stats');

        $response->assertStatus(404);
    }

    public function test_exam_stats_returns_data(): void
    {
        $exam = Exam::create([
            'title' => 'Exam Stats',
            'created_by' => 1,
        ]);

        Question::create([
            'exam_id' => $exam->id,
            'statement' => 'Q1',
        ]);

        Attempt::create([
            'exam_id' => $exam->id,
            'student_id' => 1,
            'score' => 10,
            'percentage' => 100,
        ]);

        Attempt::create([
            'exam_id' => $exam->id,
            'student_id' => 2,
            'score' => 5,
            'percentage' => 50,
        ]);

        $response = $this->getJson("/api/exams/{$exam->id}/stats");

        $response->assertStatus(200)
            ->assertJsonPath('total_attempts', 2)
            ->assertJsonPath('average_score', 7.5)
            ->assertJsonPath('highest_score', 10)
            ->assertJsonPath('lowest_score', 5)
            ->assertJsonCount(2, 'ranking');
    }

    public function test_student_stats_returns_zero_when_student_has_no_attempts(): void
    {
        $response = $this->getJson('/api/dashboard/student');

        $response->assertStatus(200)
            ->assertJsonPath('average_score', 0)
            ->assertJsonPath('pending_exams', 0)
            ->assertJsonPath('completed_exams', 0);
    }
}