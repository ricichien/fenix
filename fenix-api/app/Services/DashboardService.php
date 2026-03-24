<?php

namespace App\Services;

use App\Models\Attempt;
use App\Models\Exam;

class DashboardService
{
    public function getExamStats($examId)
    {
        // Exam::findOrFail($examId);

        $attempts = Attempt::where('exam_id', $examId)->get();

        $totalAttempts = $attempts->count();

        if ($totalAttempts === 0) {
            return [
                'total_attempts' => 0,
                'average_score' => 0,
                'average_percentage' => 0,
                'highest_score' => 0,
                'lowest_score' => 0,
                'ranking' => [],
            ];
        }

        return [
            'total_attempts' => $totalAttempts,
            'average_score' => round($attempts->avg('score'), 2),
            'average_percentage' => round($attempts->avg('percentage'), 2),
            'highest_score' => $attempts->max('score'),
            'lowest_score' => $attempts->min('score'),
            'ranking' => $attempts
                ->sortByDesc('score')
                ->values()
                ->map(function ($attempt, $index) {
                    return [
                        'position' => $index + 1,
                        'student_id' => $attempt->student_id,
                        'score' => $attempt->score,
                        'percentage' => $attempt->percentage,
                    ];
                }),
        ];
    }
    
    public function getGlobalStats($page = 1, $limit = 10)
    {
        $exams = Exam::withCount('questions')->get();
        $totalQuestionsSystem = $exams->sum('questions_count');

        if ($totalQuestionsSystem <= 0) {
            return [
                'total_attempts' => 0,
                'total_questions' => 0,
                'average_score' => 0,
                'average_percentage' => 0,
                'highest_score' => 0,
                'lowest_score' => 0,
                'ranking' => [],
            ];
        }

        $attempts = Attempt::orderBy('created_at')->get();

        $ranking = $attempts
            ->groupBy('student_id')
            ->map(function ($studentAttempts) use ($exams, $totalQuestionsSystem) {
                $attemptsByExam = $studentAttempts->groupBy('exam_id');

                $totalCorrect = 0;

                foreach ($exams as $exam) {
                    $attemptForExam = $attemptsByExam->get($exam->id)?->last();
                    $totalCorrect += $attemptForExam?->score ?? 0;
                }

                $percentage = $totalQuestionsSystem > 0
                    ? round(($totalCorrect / $totalQuestionsSystem) * 100, 2)
                    : 0;

                return [
                    'student_id' => $studentAttempts->first()->student_id,
                    'score' => $totalCorrect,
                    'percentage' => $percentage,
                    'attempts_count' => $studentAttempts->count(),
                ];
            })
            ->sort(function ($a, $b) {
                return $b['percentage'] <=> $a['percentage']
                    ?: $b['score'] <=> $a['score']
                    ?: $b['attempts_count'] <=> $a['attempts_count']
                    ?: $a['student_id'] <=> $b['student_id'];
            })
            ->values()
            ->map(function ($item, $index) {
                $item['position'] = $index + 1;
                return $item;
            });

        return [
            'total_attempts' => $attempts->count(),
            'total_questions' => $totalQuestionsSystem,
            'average_score' => round($attempts->avg('score'), 2),
            'average_percentage' => round($ranking->avg('percentage'), 2),
            'highest_score' => $attempts->max('score'),
            'lowest_score' => $attempts->min('score'),
            'ranking' => $ranking->forPage($page, $limit)->values(),
        ];
    }
}