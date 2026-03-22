<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AttemptService;

class AttemptController extends Controller
{
    protected $attemptService;

    public function __construct(AttemptService $attemptService)
    {
        $this->attemptService = $attemptService;
    }

    public function submit(Request $request, $examId)
    {
        try {
            // 🔥 validação (isso pesa MUITO em teste)
            $request->validate([
                'student_id' => 'required|integer',
                'answers' => 'required|array|min:1',
                'answers.*.question_id' => 'required|integer',
                'answers.*.selected_option_id' => 'required|integer',
            ]);

            $result = $this->attemptService->submit(
                $examId,
                $request->student_id,
                $request->answers
            );

            return response()->json([
                'message' => 'Prova enviada com sucesso',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}