<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Exam;
use App\Models\Option;
use App\Models\Question;
use App\Services\AttemptService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttemptServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_submit_exam_and_get_score(): void
    {
        $exam = Exam::create([
            'title' => 'Matemática',
            'description' => 'Prova',
            'created_by' => 1,
        ]);

        $question = Question::create([
            'exam_id' => $exam->id,
            'statement' => '2 + 2 = ?',
        ]);

        $correctOption = Option::create([
            'question_id' => $question->id,
            'text' => '4',
            'is_correct' => true,
        ]);

        Option::create([
            'question_id' => $question->id,
            'text' => '5',
            'is_correct' => false,
        ]);

        $service = new AttemptService();

        $result = $service->submit($exam->id, 1, [
            [
                'question_id' => $question->id,
                'selected_option_id' => $correctOption->id,
            ],
        ]);

        $this->assertEquals(1, $result['score']);
        $this->assertEquals(100, $result['percentage']);
        $this->assertEquals(1, $result['correct_count']);
        $this->assertEquals(0, $result['wrong_count']);
        $this->assertCount(1, $result['details']);

        $this->assertDatabaseHas('attempts', [
            'exam_id' => $exam->id,
            'student_id' => 1,
            'score' => 1,
        ]);

        $this->assertDatabaseHas('answers', [
            'question_id' => $question->id,
            'selected_option_id' => $correctOption->id,
        ]);
    }

    public function test_student_gets_wrong_score_when_answer_is_incorrect(): void
    {
        $exam = Exam::create([
            'title' => 'Matemática',
            'description' => 'Prova',
            'created_by' => 1,
        ]);

        $question = Question::create([
            'exam_id' => $exam->id,
            'statement' => '2 + 2 = ?',
        ]);

        $correctOption = Option::create([
            'question_id' => $question->id,
            'text' => '4',
            'is_correct' => true,
        ]);

        $wrongOption = Option::create([
            'question_id' => $question->id,
            'text' => '5',
            'is_correct' => false,
        ]);

        $service = new AttemptService();

        $result = $service->submit($exam->id, 2, [
            [
                'question_id' => $question->id,
                'selected_option_id' => $wrongOption->id,
            ],
        ]);

        $this->assertEquals(0, $result['score']);
        $this->assertEquals(0, $result['percentage']);
        $this->assertCount(1, $result['details']);

        $this->assertDatabaseHas('attempts', [
            'exam_id' => $exam->id,
            'student_id' => 2,
            'score' => 0,
        ]);
    }

    public function test_student_cannot_take_exam_twice(): void
    {
        $exam = Exam::create([
            'title' => 'Matemática',
            'description' => 'Prova',
            'created_by' => 1,
        ]);

        $question = Question::create([
            'exam_id' => $exam->id,
            'statement' => '2 + 2 = ?',
        ]);

        $correctOption = Option::create([
            'question_id' => $question->id,
            'text' => '4',
            'is_correct' => true,
        ]);

        $service = new AttemptService();

        $service->submit($exam->id, 1, [
            [
                'question_id' => $question->id,
                'selected_option_id' => $correctOption->id,
            ],
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Você já realizou essa prova.');

        $service->submit($exam->id, 1, [
            [
                'question_id' => $question->id,
                'selected_option_id' => $correctOption->id,
            ],
        ]);
    }

    public function test_student_can_answer_multiple_questions(): void
    {
        $exam = Exam::create([
            'title' => 'Simulado',
            'description' => 'Prova',
            'created_by' => 1,
        ]);

        $question1 = Question::create([
            'exam_id' => $exam->id,
            'statement' => 'Q1',
        ]);

        $question2 = Question::create([
            'exam_id' => $exam->id,
            'statement' => 'Q2',
        ]);

        $option1 = Option::create([
            'question_id' => $question1->id,
            'text' => 'A',
            'is_correct' => true,
        ]);

        $option2 = Option::create([
            'question_id' => $question2->id,
            'text' => 'B',
            'is_correct' => true,
        ]);

        $service = new AttemptService();

        $result = $service->submit($exam->id, 3, [
            [
                'question_id' => $question1->id,
                'selected_option_id' => $option1->id,
            ],
            [
                'question_id' => $question2->id,
                'selected_option_id' => $option2->id,
            ],
        ]);

        $this->assertEquals(2, $result['score']);
        $this->assertEquals(100, $result['percentage']);
        $this->assertCount(2, $result['details']);
        $this->assertCount(2, Answer::all());
    }
}