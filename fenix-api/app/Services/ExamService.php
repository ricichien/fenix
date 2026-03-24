<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\Attempt;
use DomainException;
use Illuminate\Support\Facades\DB;

class ExamService
{
    private function ensureNoAttempts(int $examId): void
    {
        $hasAttempts = Attempt::where('exam_id', $examId)->exists();

        if ($hasAttempts) {
            throw new DomainException('Não é possível editar ou excluir esta prova porque ela já possui respostas.');
        }
    }

    public function create(array $data): Exam
    {
        return DB::transaction(function () use ($data) {
            $exam = Exam::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'created_by' => 1,
            ]);

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

    public function update(int $id, array $data): Exam
    {
        return DB::transaction(function () use ($id, $data) {
            $this->ensureNoAttempts($id);

            $exam = Exam::with('questions.options')->findOrFail($id);

            $exam->update([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
            ]);

            foreach ($exam->questions as $question) {
                $question->options()->delete();
            }
            $exam->questions()->delete();

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

    public function delete(int $id): void
    {
        DB::transaction(function () use ($id) {
            $this->ensureNoAttempts($id);

            $exam = Exam::with('questions.options')->findOrFail($id);

            foreach ($exam->questions as $question) {
                $question->options()->delete();
            }

            $exam->questions()->delete();
            $exam->delete();
        });
    }
}

// namespace App\Services;

// use App\Models\Exam;
// use Illuminate\Support\Facades\DB;

// class ExamService
// {
//     public function create(array $data): Exam
//     {
//         return DB::transaction(function () use ($data) {

//             $exam = Exam::create([
//                 'title' => $data['title'],
//                 'description' => $data['description'] ?? null,
//                 'created_by' => 1,
//             ]);

//             if (!empty($data['questions'])) {
//                 foreach ($data['questions'] as $questionData) {

//                     $question = $exam->questions()->create([
//                         'statement' => $questionData['statement'],
//                     ]);

//                     if (!empty($questionData['options'])) {
//                         foreach ($questionData['options'] as $optionData) {
//                             $question->options()->create([
//                                 'text' => $optionData['text'],
//                                 'is_correct' => $optionData['is_correct'] ?? false,
//                             ]);
//                         }
//                     }
//                 }
//             }

//             return $exam->load('questions.options');
//         });
//     }

//      public function getAll()
//     {
//         $exams = Exam::with('questions.options')->get();

//         $exams->each(function ($exam) {
//             $exam->questions->each(function ($question) {
//                 $question->options->makeHidden(['is_correct']);
//             });
//         });

//         return $exams;
//     }

//     public function update(int $id, array $data): Exam
//     {
//         return DB::transaction(function () use ($id, $data) {

//             $exam = Exam::with('questions.options')->findOrFail($id);

//             $exam->update([
//                 'title' => $data['title'],
//                 'description' => $data['description'] ?? null,
//             ]);

//             if (!isset($data['questions'])) {
//                 return $exam->load('questions.options');
//             }

//             foreach ($exam->questions as $question) {
//                 $question->options()->delete();
//             }
//             $exam->questions()->delete();

//             foreach ($data['questions'] as $questionData) {

//                 $question = $exam->questions()->create([
//                     'statement' => $questionData['statement'],
//                 ]);

//                 foreach ($questionData['options'] as $optionData) {
//                     $question->options()->create([
//                         'text' => $optionData['text'],
//                         'is_correct' => $optionData['is_correct'] ?? false,
//                     ]);
//                 }
//             }

//             return $exam->load('questions.options');
//         });
//     }
// }