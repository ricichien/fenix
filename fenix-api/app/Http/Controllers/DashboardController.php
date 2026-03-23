<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Models\Exam;
use OpenApi\Annotations as OA;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }
    /**
     * @OA\Get(
     *     path="/dashboard",
     *     tags={"Dashboard"},
     *     summary="Retorna estatísticas globais",
     *     @OA\Response(
     *         response=200,
     *         description="Estatísticas globais das tentativas",
     *         @OA\JsonContent(ref="#/components/schemas/DashboardStats")
     *     )
     * )
     */
    public function index()
    {
        $stats = $this->dashboardService->getGlobalStats();

        return response()->json($stats);
    }
    /**
     * @OA\Get(
     *     path="/exams/{examId}/stats",
     *     tags={"Dashboard"},
     *     summary="Retorna estatísticas de um exame específico",
     *     @OA\Parameter(
     *         name="examId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estatísticas do exame",
     *         @OA\JsonContent(ref="#/components/schemas/DashboardStats")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Exame não encontrado"
     *     )
     * )
     */
    public function examStats($examId)
    {
        Exam::findOrFail($examId);
        
        $stats = $this->dashboardService->getExamStats($examId);

        return response()->json($stats);
    }
}