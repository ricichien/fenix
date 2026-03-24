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

    public function index()
    {
        $studentId = request()->query('student_id');

        $exams = Exam::with(['questions.options'])->withCount('attempts')->get();

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
            $data['has_attempts'] = $exam->attempts_count > 0;
            $data['has_attempt'] = in_array($exam->id, $attemptedExamIds);

            return $data;
        });

        return response()->json($payload);
    }

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

    public function showWithAnswers($id)
    {
        $exam = Exam::with('questions.options')->findOrFail($id);

        return response()->json($exam);
    }

    public function debug()
    {
        return response()->json([
            'questions' => \App\Models\Question::all(),
            'options' => \App\Models\Option::all(),
        ]);
    }

    public function destroy($id)
    {
        try {
            $this->examService->delete($id);

            return response()->json([
                'message' => 'Exam deleted successfully'
            ]);
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }

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

        try {
            $exam = $this->examService->update($id, $validated);
            return response()->json($exam);
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
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