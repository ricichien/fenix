<?php

namespace App\Services;

use App\Models\Attempt;
use App\Models\Question;
use App\Models\Answer;

class AttemptService
{
    public function submit(int $examId, int $studentId, array $answers)
    {
        // regra: só pode fazer uma vez
        $exists = Attempt::where('exam_id', $examId)
            ->where('student_id', $studentId)
            ->exists();

        if ($exists) {
            throw new \Exception('Você já realizou essa prova.');
        }

        $score = 0;
        $totalQuestions = count($answers);

        // cria tentativa
        $attempt = Attempt::create([
            'exam_id' => $examId,
            'student_id' => $studentId,
            'score' => 0,
            'percentage' => 0,
        ]);

        foreach ($answers as $answer) {
            $question = Question::with('options')->find($answer['question_id']);

            $correctOption = $question->options->firstWhere('is_correct', true);

            if ($correctOption && $correctOption->id == $answer['selected_option_id']) {
                $score++;
            }

            Answer::create([
                'attempt_id' => $attempt->id,
                'question_id' => $answer['question_id'],
                'selected_option_id' => $answer['selected_option_id'],
            ]);
        }

        $percentage = ($score / $totalQuestions) * 100;

        $attempt->update([
            'score' => $score,
            'percentage' => $percentage,
        ]);

        return $attempt;
    }
}