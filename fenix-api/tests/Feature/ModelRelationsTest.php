<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Option;
use App\Models\Attempt;

class ModelRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_exam_has_questions_and_options()
    {
        $exam = Exam::create([
            'title' => 'Test',
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

        $this->assertCount(1, $exam->questions);
        $this->assertEquals($question->id, $exam->questions->first()->id);

        $this->assertCount(1, $question->options);
        $this->assertEquals($option->id, $question->options->first()->id);
    }

    public function test_attempt_belongs_to_exam()
    {
        $exam = Exam::create([
            'title' => 'Test',
            'created_by' => 1,
        ]);

        $attempt = Attempt::create([
            'exam_id' => $exam->id,
            'student_id' => 1,
            'score' => 5,
            'percentage' => 50,
        ]);

        $this->assertEquals($exam->id, $attempt->exam->id);
    }
    public function test_inverse_relationships()
    {
        $exam = \App\Models\Exam::create([
            'title' => 'Test',
            'created_by' => 1,
        ]);

        $question = \App\Models\Question::create([
            'exam_id' => $exam->id,
            'statement' => 'Q1',
        ]);

        $option = \App\Models\Option::create([
            'question_id' => $question->id,
            'text' => 'A',
            'is_correct' => true,
        ]);

        $this->assertEquals($exam->id, $question->exam->id);

        $this->assertEquals($question->id, $option->question->id);
    }
}