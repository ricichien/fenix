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
    
    // public function getGlobalStats($page = 1, $limit = 10)
    // {
    //     $allAttempts = Attempt::all();

    //     if ($allAttempts->isEmpty()) {
    //         return [
    //             'total_attempts' => 0,
    //             'average_score' => 0,
    //             'average_percentage' => 0,
    //             'highest_score' => 0,
    //             'lowest_score' => 0,
    //             'ranking' => [],
    //         ];
    //     }

    //     $ranking = $allAttempts
    //         ->groupBy('student_id')
    //         ->map(function ($studentAttempts) {
    //             return [
    //                 'student_id' => $studentAttempts->first()->student_id,
    //                 'score' => round($studentAttempts->avg('score'), 2),
    //                 'percentage' => round($studentAttempts->avg('percentage'), 2),
    //                 'attempts_count' => $studentAttempts->count(),
    //             ];
    //         })
    //         ->sort(function ($a, $b) {
    //             return $b['score'] <=> $a['score']
    //                 ?: $b['percentage'] <=> $a['percentage']
    //                 ?: $b['attempts_count'] <=> $a['attempts_count']
    //                 ?: $a['student_id'] <=> $b['student_id'];
    //         })
    //         ->values()
    //         ->map(function ($item, $index) {
    //             $item['position'] = $index + 1;
    //             return $item;
    //         });

    //     return [
    //         'total_attempts' => $allAttempts->count(),
    //         'average_score' => round($allAttempts->avg('score'), 2),
    //         'average_percentage' => round($allAttempts->avg('percentage'), 2),
    //         'highest_score' => $allAttempts->max('score'),
    //         'lowest_score' => $allAttempts->min('score'),
    //         'ranking' => $ranking->forPage($page, $limit)->values(),
    //     ];
    // }
    public function getGlobalStats($page = 1, $limit = 10)
    {
        $allAttempts = Attempt::all();

        if ($allAttempts->isEmpty()) {
            return [
                'total_attempts' => 0,
                'average_score' => 0,
                'average_percentage' => 0,
                'highest_score' => 0,
                'lowest_score' => 0,
                'ranking' => [],
            ];
        }

        $ranking = $allAttempts
            ->groupBy('student_id')
            ->map(function ($studentAttempts) {

                dd($studentAttempts->map(fn($a) => [
                    'score' => $a->score,
                    'total_questions' => $a->total_questions
                ]));

                $totalCorrect = $studentAttempts->sum('score');
                $totalQuestions = $studentAttempts->sum('total_questions');

                $percentage = $totalQuestions > 0
                    ? round(($totalCorrect / $totalQuestions) * 100, 2)
                    : 0;

                return [
                    'student_id' => $studentAttempts->first()->student_id,
                    'score' => $totalCorrect, // TOTAL, não média
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
            'total_attempts' => $allAttempts->count(),

            // média global continua média normal
            'average_score' => round($allAttempts->avg('score'), 2),
            'average_percentage' => round($allAttempts->avg('percentage'), 2),

            'highest_score' => $allAttempts->max('score'),
            'lowest_score' => $allAttempts->min('score'),

            'ranking' => $ranking->forPage($page, $limit)->values(),
        ];
    }
}