<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\Question;
use App\Models\Option;
use App\Services\ExamService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_can_create_exam_with_questions_and_options(): void
    {
        $service = new ExamService();

        $exam = $service->create([
            'title' => 'Matemática',
            'description' => 'Prova de matemática',
            'questions' => [
                [
                    'statement' => '2 + 2 = ?',
                    'options' => [
                        ['text' => '4', 'is_correct' => true],
                        ['text' => '5', 'is_correct' => false],
                    ],
                ],
            ],
        ]);

        $this->assertInstanceOf(Exam::class, $exam);
        $this->assertEquals('Matemática', $exam->title);
        $this->assertCount(1, $exam->questions);
        $this->assertCount(2, $exam->questions[0]->options);
    }

    public function test_get_all_hides_is_correct(): void
    {
        $service = new ExamService();

        $service->create([
            'title' => 'Geografia',
            'description' => 'Prova de geografia',
            'questions' => [
                [
                    'statement' => 'Onde fica Ourinhos?',
                    'options' => [
                        ['text' => 'SP', 'is_correct' => true],
                        ['text' => 'PR', 'is_correct' => false],
                    ],
                ],
            ],
        ]);

        $exams = $service->getAll();

        $this->assertCount(1, $exams);
        $this->assertArrayNotHasKey('is_correct', $exams[0]->questions[0]->options[0]->toArray());
    }

    public function test_can_update_exam_and_replace_questions(): void
    {
        $service = new ExamService();

        $exam = $service->create([
            'title' => 'Antigo',
            'description' => 'Descrição antiga',
            'questions' => [
                [
                    'statement' => 'Pergunta antiga',
                    'options' => [
                        ['text' => 'A', 'is_correct' => true],
                        ['text' => 'B', 'is_correct' => false],
                    ],
                ],
            ],
        ]);

        $updated = $service->update($exam->id, [
            'title' => 'Novo',
            'description' => 'Descrição nova',
            'questions' => [
                [
                    'statement' => 'Pergunta nova',
                    'options' => [
                        ['text' => 'X', 'is_correct' => true],
                        ['text' => 'Y', 'is_correct' => false],
                    ],
                ],
            ],
        ]);

        $this->assertEquals('Novo', $updated->title);
        $this->assertCount(1, $updated->questions);
        $this->assertEquals('Pergunta nova', $updated->questions[0]->statement);
        $this->assertCount(2, $updated->questions[0]->options);
    }

    public function test_update_without_questions_keeps_existing_questions(): void
    {
        $service = new ExamService();

        $exam = Exam::create([
            'title' => 'Original',
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

        $service->update($exam->id, [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
        ]);

        $exam->refresh();

        $this->assertEquals('Updated Title', $exam->title);
        $this->assertCount(1, $exam->questions);
    }
    public function test_update_does_not_remove_questions_when_not_provided(): void
    {
        $service = new \App\Services\ExamService();

        $exam = \App\Models\Exam::create([
            'title' => 'Test',
            'created_by' => 1,
        ]);

        $question = \App\Models\Question::create([
            'exam_id' => $exam->id,
            'statement' => 'Q1',
        ]);

        \App\Models\Option::create([
            'question_id' => $question->id,
            'text' => 'A',
            'is_correct' => true,
        ]);

        // 🔥 update SEM questions
        $service->update($exam->id, [
            'title' => 'Updated',
            'description' => null,
        ]);

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
        ]);
    }
}