<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Option;
use App\Services\DashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class DashboardServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_exam_stats_returns_zero_when_there_are_no_attempts(): void
    {
        $service = new DashboardService();

        $stats = $service->getExamStats(999);

        $this->assertEquals(0, $stats['total_attempts']);
        $this->assertEquals(0, $stats['average_score']);
        $this->assertEquals(0, $stats['average_percentage']);
        $this->assertEquals(0, $stats['highest_score']);
        $this->assertEquals(0, $stats['lowest_score']);
        $this->assertCount(0, $stats['ranking']);
    }

    public function test_exam_stats_returns_ranking_and_averages(): void
    {
        $exam = Exam::create([
            'title' => 'Test Exam',
            'created_by' => 1,
        ]);

        $question = Question::create([
            'exam_id' => $exam->id,
            'statement' => 'Q1',
        ]);

        $option = Option::create([
            'question_id' => $question->id,
            'text' => 'A',
            'is_correct' => true,
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
            'score' => 8,
            'percentage' => 80,
        ]);

        Attempt::create([
            'exam_id' => $exam->id,
            'student_id' => 3,
            'score' => 6,
            'percentage' => 60,
        ]);

        $service = new DashboardService();

        $stats = $service->getExamStats($exam->id);

        $this->assertEquals(3, $stats['total_attempts']);
        $this->assertEquals(8, $stats['average_score']);
        $this->assertEquals(80, $stats['average_percentage']);
        $this->assertEquals(10, $stats['highest_score']);
        $this->assertEquals(6, $stats['lowest_score']);

        $this->assertEquals(1, $stats['ranking'][0]['student_id']);
        $this->assertEquals(2, $stats['ranking'][1]['student_id']);
        $this->assertEquals(3, $stats['ranking'][2]['student_id']);
    }

    public function test_global_stats_returns_zero_when_there_are_no_attempts(): void
    {
        $service = new DashboardService();

        $stats = $service->getGlobalStats();

        $this->assertEquals(0, $stats['total_attempts']);
        $this->assertCount(0, $stats['ranking']);
    }

    public function test_cache_stores_dashboard_data(): void
    {
        Cache::put('dashboard_test', ['value' => 123], 10);

        $this->assertEquals(123, Cache::get('dashboard_test')['value']);
    }

    public function test_global_stats_with_data(): void
    {
        $exam = Exam::create([
            'title' => 'Global',
            'created_by' => 1,
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

        $service = new DashboardService();

        $stats = $service->getGlobalStats();

        $this->assertEquals(2, $stats['total_attempts']);
    }

    public function test_exam_with_single_attempt(): void
    {
        $exam = Exam::create([
            'title' => 'Single',
            'created_by' => 1,
        ]);

        Attempt::create([
            'exam_id' => $exam->id,
            'student_id' => 1,
            'score' => 9,
            'percentage' => 90,
        ]);

        $service = new DashboardService();

        $stats = $service->getExamStats($exam->id);

        $this->assertEquals(1, $stats['total_attempts']);
        $this->assertEquals(9, $stats['highest_score']);
        $this->assertEquals(9, $stats['lowest_score']);
    }

    public function test_average_is_calculated_with_decimals(): void
    {
        $exam = Exam::create([
            'title' => 'Decimal Test',
            'created_by' => 1,
        ]);

        Attempt::create([
            'exam_id' => $exam->id,
            'student_id' => 1,
            'score' => 7,
            'percentage' => 70,
        ]);

        Attempt::create([
            'exam_id' => $exam->id,
            'student_id' => 2,
            'score' => 8,
            'percentage' => 80,
        ]);

        $service = new DashboardService();

        $stats = $service->getExamStats($exam->id);

        $this->assertEquals(7.5, $stats['average_score']);
        $this->assertEquals(75, $stats['average_percentage']);
    }
    public function test_exam_stats_handles_tie_in_scores(): void
    {
        $exam = Exam::create([
            'title' => 'Tie Test',
            'created_by' => 1,
        ]);

        $question = Question::create([
            'exam_id' => $exam->id,
            'statement' => 'Q1',
        ]);

        Option::create([
            'question_id' => $question->id,
            'text' => 'A',
            'is_correct' => true,
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
            'score' => 10,
            'percentage' => 100,
        ]);

        $service = new DashboardService();

        $stats = $service->getExamStats($exam->id);

        $this->assertCount(2, $stats['ranking']);
        $this->assertEquals(1, $stats['ranking'][0]['position']);
        $this->assertEquals(2, $stats['ranking'][1]['position']);
    }
}