<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AttemptService;
use OpenApi\Annotations as OA;

class AttemptController extends Controller
{
    protected $attemptService;

    public function __construct(AttemptService $attemptService)
    {
        $this->attemptService = $attemptService;
    }
    /**
     * @OA\Post(
     *     path="/exams/{examId}/submit",
     *     tags={"Attempts"},
     *     summary="Envia as respostas de uma prova",
     *     @OA\Parameter(
     *         name="examId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AttemptSubmitRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tentativa processada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Prova enviada com sucesso"),
     *             @OA\Property(property="data", ref="#/components/schemas/AttemptResult")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de regra de negócio ou exceção capturada no controller",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Você já realizou essa prova.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validação falhou"
     *     )
     * )
     */
    public function submit(Request $request, $examId)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer',
            'answers' => 'required|array|min:1',
            'answers.*.question_id' => 'required|integer',
            'answers.*.selected_option_id' => 'required|integer',
        ]);

        try {
            $result = $this->attemptService->submit(
                $examId,
                $validated['student_id'],
                $validated['answers']
            );

            return response()->json([
                'message' => 'Prova enviada com sucesso',
                'data' => $result
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
    // public function submit(Request $request, $examId)
    // {
    //     $request->validate([
    //         'student_id' => 'required|integer',
    //         'answers' => 'required|array|min:1',
    //         'answers.*.question_id' => 'required|integer',
    //         'answers.*.selected_option_id' => 'required|integer',
    //     ]);

    //     try {
    //         $result = $this->attemptService->submit($examId, $data);

    //         return response()->json([
    //             'message' => 'Prova enviada com sucesso',
    //             'data' => $result
    //         ], 201);

    //     } catch (ModelNotFoundException $e) {
    //         return response()->json([
    //             'message' => 'Exame não encontrado'
    //         ], 404);

    //     } catch (\DomainException $e) {
    //         return response()->json([
    //             'message' => $e->getMessage()
    //         ], 400);

    //     } catch (ValidationException $e) {
    //         throw $e;

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Erro interno'
    //         ], 500);
    //     }
    // }
}