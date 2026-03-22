<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function examStats($examId)
    {
        $stats = $this->dashboardService->getExamStats($examId);

        return response()->json($stats);
    }
}