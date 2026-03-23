<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Services\QuestionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_question_with_options(): void
    {
        $exam = Exam::create([
            'title' => 'Português',
            'description' => 'Prova',
            'created_by' => 1,
        ]);

        $service = new QuestionService();

        $question = $service->create([
            'exam_id' => $exam->id,
            'statement' => 'Qual é a vogal?',
            'options' => [
                ['text' => 'A', 'is_correct' => true],
                ['text' => 'B', 'is_correct' => false],
            ],
        ]);

        $this->assertEquals($exam->id, $question->exam_id);
        $this->assertEquals('Qual é a vogal?', $question->statement);
        $this->assertCount(2, $question->options);
    }
}