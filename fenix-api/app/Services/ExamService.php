<?php

namespace App\Services;

use App\Models\Exam;

class ExamService
{
    public function create(array $data): Exam
    {
        return Exam::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'created_by' => 1,
        ]);
    }

    public function getAll()
    {
        return Exam::with('questions.options')->get();
    }

    public function update(int $id, array $data): Exam
    {
        $exam = Exam::with('questions.options')->findOrFail($id);

        $exam->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);

        // remove tudo antigo
        foreach ($exam->questions as $question) {
            $question->options()->delete();
        }
        $exam->questions()->delete();

        // recria tudo
        foreach ($data['questions'] as $questionData) {
            $question = $exam->questions()->create([
                'statement' => $questionData['statement']
            ]);

            foreach ($questionData['options'] as $optionData) {
                $question->options()->create([
                    'text' => $optionData['text'],
                    'is_correct' => $optionData['is_correct']
                ]);
            }
        }

        return $exam->load('questions.options');
    }
}