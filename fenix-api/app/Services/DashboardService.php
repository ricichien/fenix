<?php

namespace App\Services;

use App\Models\Attempt;

class DashboardService
{
    public function getExamStats(int $examId)
    {
        $attempts = Attempt::where('exam_id', $examId)->get();

        $totalAttempts = $attempts->count();
        $averageScore = $attempts->avg('score');
        $averagePercentage = $attempts->avg('percentage');
        $highestScore = $attempts->max('score');

        return [
            'total_attempts' => $totalAttempts,
            'average_score' => $averageScore,
            'average_percentage' => $averagePercentage,
            'highest_score' => $highestScore,
        ];
    }
    public function getRanking(int $examId)
    {
        return \App\Models\Attempt::where('exam_id', $examId)
            ->orderByDesc('score')
            ->paginate(10);
    }
}