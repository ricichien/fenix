<?php

namespace App\Services;

use App\Models\Attempt;

class DashboardService
{
    public function getExamStats($examId)
    {
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

    public function getGlobalStats()
    {
        $attempts = Attempt::all();

        if ($attempts->count() === 0) {
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
            'total_attempts' => $attempts->count(),
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
}