<?php

namespace App\Services;

use App\Models\Exam;
use Illuminate\Support\Facades\DB;

class ExamService
{
    public function create(array $data): Exam
    {
        return DB::transaction(function () use ($data) {

            $exam = Exam::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'created_by' => 1,
            ]);

            // cria perguntas + opções
            if (!empty($data['questions'])) {
                foreach ($data['questions'] as $questionData) {

                    $question = $exam->questions()->create([
                        'statement' => $questionData['statement'],
                    ]);

                    if (!empty($questionData['options'])) {
                        foreach ($questionData['options'] as $optionData) {
                            $question->options()->create([
                                'text' => $optionData['text'],
                                'is_correct' => $optionData['is_correct'] ?? false,
                            ]);
                        }
                    }
                }
            }

            return $exam->load('questions.options');
        });
    }

    // public function getAll()
    // {
    //     return Exam::with('questions.options')->get();
    // }
     public function getAll()
    {
        $exams = Exam::with('questions.options')->get();

        $exams->each(function ($exam) {
            $exam->questions->each(function ($question) {
                $question->options->makeHidden(['is_correct']);
            });
        });

        return $exams;
    }

    public function update(int $id, array $data): Exam
    {
        return DB::transaction(function () use ($id, $data) {

            $exam = Exam::with('questions.options')->findOrFail($id);

            $exam->update([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
            ]);

            // segurança: se não vier questions, não mexe nelas
            if (!isset($data['questions'])) {
                return $exam->load('questions.options');
            }

            // remove tudo antigo
            foreach ($exam->questions as $question) {
                $question->options()->delete();
            }
            $exam->questions()->delete();

            // recria tudo
            foreach ($data['questions'] as $questionData) {

                $question = $exam->questions()->create([
                    'statement' => $questionData['statement'],
                ]);

                foreach ($questionData['options'] as $optionData) {
                    $question->options()->create([
                        'text' => $optionData['text'],
                        'is_correct' => $optionData['is_correct'] ?? false,
                    ]);
                }
            }

            return $exam->load('questions.options');
        });
    }
}