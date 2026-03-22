<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Option;

class QuestionService
{
    public function create(array $data)
    {
        $question = Question::create([
            'exam_id' => $data['exam_id'],
            'statement' => $data['statement'],
        ]);

        foreach ($data['options'] as $option) {
            Option::create([
                'question_id' => $question->id,
                'text' => $option['text'],
                'is_correct' => $option['is_correct'],
            ]);
        }

        return $question->load('options');
    }
}