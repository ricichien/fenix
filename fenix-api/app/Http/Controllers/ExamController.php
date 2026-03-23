<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExamService;
use App\Models\Exam;
use App\Models\Attempt;
use OpenApi\Annotations as OA;

class ExamController extends Controller
{
    protected $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }
    /**
     * @OA\Post(
     *     path="/exams",
     *     tags={"Exams"},
     *     summary="Cria um novo exame com questões e alternativas",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ExamWriteRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exame criado e retornado com questões e alternativas",
     *         @OA\JsonContent(ref="#/components/schemas/ExamWithAnswers")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Se houver validação automática ou payload inválido em algum ponto do fluxo",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'title' => 'required|string',
        'description' => 'nullable|string',
        'questions' => 'required|array|min:1',
        'questions.*.statement' => 'required|string',
        'questions.*.options' => 'required|array|min:2',
        'questions.*.options.*.text' => 'required|string',
        'questions.*.options.*.is_correct' => 'required|boolean',
    ]);

    $exam = $this->examService->create($validated);

        return response()->json($exam, 200);
    }
    /**
     * @OA\Get(
     *     path="/exams",
     *     tags={"Exams"},
     *     summary="Lista todos os exames",
     *     @OA\Parameter(
     *         name="student_id",
     *         in="query",
     *         required=false,
     *         description="Se informado, marca has_attempt=true para exames já realizados por esse estudante",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de exames",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ExamListItem")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $studentId = request()->query('student_id');

        $exams = Exam::with('questions.options')->get();

        $attemptedExamIds = [];
        if ($studentId) {
            $attemptedExamIds = Attempt::where('student_id', $studentId)
                ->pluck('exam_id')
                ->toArray();
        }

        $payload = $exams->map(function ($exam) use ($attemptedExamIds) {
            $exam->questions->each(function ($question) {
                $question->options->makeHidden(['is_correct']);
            });

            $data = $exam->toArray();
            $data['has_attempt'] = in_array($exam->id, $attemptedExamIds);

            return $data;
        });

        return response()->json($payload);
    }
    /**
     * @OA\Get(
     *     path="/exams/{id}",
     *     tags={"Exams"},
     *     summary="Exibe um exame sem revelar as alternativas corretas",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="student_id",
     *         in="query",
     *         required=false,
     *         description="Se informado, retorna has_attempt e last_result com base na última tentativa do estudante",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exame encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/ExamListItem")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Exame não encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $studentId = request()->query('student_id');

        $exam = Exam::with('questions.options')->findOrFail($id);

        $attempt = null;
        if ($studentId) {
            $attempt = Attempt::with('answers')
                ->where('exam_id', $id)
                ->where('student_id', $studentId)
                ->latest()
                ->first();
        }

        $lastResult = $attempt
            ? $this->buildResultFromAttempt($exam, $attempt)
            : null;

        $exam->questions->each(function ($question) {
            $question->options->makeHidden(['is_correct']);
        });

        $data = $exam->toArray();
        $data['has_attempt'] = $attempt ? true : false;
        $data['last_result'] = $lastResult;

        return response()->json($data);
    }
    /**
     * @OA\Get(
     *     path="/exams/{id}/edit",
     *     tags={"Exams"},
     *     summary="Exibe o exame com as alternativas corretas visíveis",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exame com `is_correct` em cada alternativa",
     *         @OA\JsonContent(ref="#/components/schemas/ExamWithAnswers")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Exame não encontrado"
     *     )
     * )
     */
    public function showWithAnswers($id)
    {
        $exam = Exam::with('questions.options')->findOrFail($id);

        return response()->json($exam);
    }
    /**
     * @OA\Get(
     *     path="/debug",
     *     tags={"Exams"},
     *     summary="Retorna todas as questões e opções em modo debug",
     *     @OA\Response(
     *         response=200,
     *         description="Dados brutos de questões e opções",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="questions",
     *                 type="array",
     *                 @OA\Items(type="object")
     *             ),
     *             @OA\Property(
     *                 property="options",
     *                 type="array",
     *                 @OA\Items(type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function debug()
    {
        return response()->json([
            'questions' => \App\Models\Question::all(),
            'options' => \App\Models\Option::all(),
        ]);
    }
    /**
     * @OA\Delete(
     *     path="/exams/{id}",
     *     tags={"Exams"},
     *     summary="Remove um exame e seus filhos",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exame removido",
     *         @OA\JsonContent(ref="#/components/schemas/GenericMessageResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Exame não encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $exam = Exam::with('questions.options')->findOrFail($id);

        foreach ($exam->questions as $question) {
            $question->options()->delete();
        }

        $exam->questions()->delete();
        $exam->delete();

        return response()->json([
            'message' => 'Exam deleted successfully'
        ]);
    }
    /**
     * @OA\Put(
     *     path="/exams/{id}",
     *     tags={"Exams"},
     *     summary="Atualiza um exame e recria suas questões/alternativas",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ExamWriteRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exame atualizado",
     *         @OA\JsonContent(ref="#/components/schemas/ExamWithAnswers")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Exame não encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.statement' => 'required|string',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*.text' => 'required|string',
            'questions.*.options.*.is_correct' => 'required|boolean',
        ]);

        $exam = $this->examService->update($id, $validated);

        return response()->json($exam);
    }

    private function buildResultFromAttempt(Exam $exam, Attempt $attempt): array
    {
        $attemptAnswers = $attempt->answers->keyBy('question_id');

        $score = 0;
        $details = [];

        foreach ($exam->questions as $question) {
            $answer = $attemptAnswers->get($question->id);

            $selectedOption = null;
            if ($answer) {
                $selectedOption = $question->options->firstWhere('id', $answer->selected_option_id);
            }

            $correctOption = $question->options->firstWhere('is_correct', true);

            $isCorrect = $correctOption && $selectedOption && $correctOption->id === $selectedOption->id;

            if ($isCorrect) {
                $score++;
            }

            $details[] = [
                'question_id' => $question->id,
                'question_statement' => $question->statement,
                'selected_option_id' => $selectedOption?->id,
                'selected_text' => $selectedOption?->text,
                'correct_option_id' => $correctOption?->id,
                'correct_text' => $correctOption?->text,
                'is_correct' => $isCorrect,
            ];
        }

        $totalQuestions = $exam->questions->count();
        $percentage = $totalQuestions > 0 ? round(($score / $totalQuestions) * 100) : 0;

        return [
            'attempt_id' => $attempt->id,
            'exam_id' => $attempt->exam_id,
            'student_id' => $attempt->student_id,
            'score' => $score,
            'percentage' => $percentage,
            'total_questions' => $totalQuestions,
            'correct_count' => $score,
            'wrong_count' => $totalQuestions - $score,
            'details' => $details,
        ];
    }
}