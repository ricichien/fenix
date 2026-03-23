<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuestionService;
use OpenApi\Annotations as OA;

class QuestionController extends Controller
{
    protected $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }
    /**
     * @OA\Post(
     *     path="/questions",
     *     tags={"Questions"},
     *     summary="Cria uma questão com alternativas",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/QuestionCreateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Questão criada",
     *         @OA\JsonContent(ref="#/components/schemas/QuestionWithAnswers")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validação falhou"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'statement' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*.text' => 'required|string',
            'options.*.is_correct' => 'required|boolean',
        ]);

        return $this->questionService->create($validated);
    }
}