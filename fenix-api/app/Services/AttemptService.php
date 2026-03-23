<?php

namespace App\Services;

use App\Models\Attempt;
use App\Models\Answer;
use App\Models\Exam;
use Exception;
use Illuminate\Support\Facades\DB;

class AttemptService
{
    public function submit(int $examId, int $studentId, array $submittedAnswers): array
    {
        return DB::transaction(function () use ($examId, $studentId, $submittedAnswers) {
            $exists = Attempt::where('exam_id', $examId)
                ->where('student_id', $studentId)
                ->exists();

            if ($exists) {
                throw new Exception('Você já realizou essa prova.');
            }

            $exam = Exam::with('questions.options')->findOrFail($examId);

            $attempt = Attempt::create([
                'exam_id' => $examId,
                'student_id' => $studentId,
                'score' => 0,
                'percentage' => 0,
            ]);

            $submittedByQuestion = collect($submittedAnswers)->keyBy('question_id');

            $score = 0;
            $details = [];
            $totalQuestions = $exam->questions->count();

            foreach ($exam->questions as $question) {
                $answer = $submittedByQuestion->get($question->id);

                $selectedOption = null;
                if ($answer) {
                    $selectedOption = $question->options->firstWhere('id', $answer['selected_option_id']);
                }

                $correctOption = $question->options->firstWhere('is_correct', true);

                $isCorrect = $correctOption && $selectedOption && $correctOption->id === $selectedOption->id;

                if ($isCorrect) {
                    $score++;
                }

                if ($answer) {
                    Answer::create([
                        'attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                        'selected_option_id' => $answer['selected_option_id'],
                    ]);
                }

                $details[] = [
                    'question_id' => $question->id,
                    'question_statement' => $question->statement,
                    'selected_option_id' => $selectedOption?->id,
                    'selected_text' => $selectedOption?->text,
                    'correct_option_id' => $correctOption?->id,
                    'correct_text' => $correctOption?->text,
                    'is_correct' => $isCorrect,
                ];
            }

            $percentage = $totalQuestions > 0
                ? round(($score / $totalQuestions) * 100)
                : 0;

            $attempt->update([
                'score' => $score,
                'percentage' => $percentage,
            ]);

            return [
                'attempt_id' => $attempt->id,
                'exam_id' => $attempt->exam_id,
                'student_id' => $attempt->student_id,
                'score' => $score,
                'percentage' => $percentage,
                'total_questions' => $totalQuestions,
                'correct_count' => $score,
                'wrong_count' => $totalQuestions - $score,
                'details' => $details,
            ];
        });
    }
}