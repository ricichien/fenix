<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Models\Exam;
use OpenApi\Annotations as OA;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(\Illuminate\Http\Request $request)
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);

        $stats = $this->dashboardService->getGlobalStats($page, $limit);

        return response()->json($stats);
    }

    public function examStats($examId)
    {
        Exam::findOrFail($examId);
        
        $stats = $this->dashboardService->getExamStats($examId);

        return response()->json($stats);
    }

    public function studentStats(Request $request)
    {
        $studentId = 1;

        $attempts = \App\Models\Attempt::where('student_id', $studentId)->get();

        if ($attempts->isEmpty()) {
            return response()->json([
                'average_score' => 0,
                'pending_exams' => 0,
                'completed_exams' => 0,
            ]);
        }

        $averageScore = round($attempts->avg('score'), 2);

        $completedExams = $attempts->count();

        $totalExams = \App\Models\Exam::count();

        $pendingExams = $totalExams - $completedExams;

        return response()->json([
            'average_score' => $averageScore,
            'pending_exams' => $pendingExams,
            'completed_exams' => $completedExams,
        ]);
    }
}