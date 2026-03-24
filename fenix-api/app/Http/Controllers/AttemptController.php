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
}