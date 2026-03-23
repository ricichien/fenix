<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExamService;
use App\Models\Exam;

class ExamController extends Controller
{
    protected $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    public function store(Request $request)
    {
        $exam = $this->examService->create($request->all());

        return response()->json($exam);
    }

    public function index()
    {
        return response()->json($this->examService->getAll());
    }
    public function show($id)
    {
        $exam = Exam::with('questions.options')->findOrFail($id);

        $exam->questions->each(function ($question) {
            $question->options->each(function ($option) {
                unset($option->is_correct);
            });
        });

        return response()->json($exam);
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
        $exam = Exam::with('questions.options')->findOrFail($id);

        // Deleta opções
        foreach ($exam->questions as $question) {
            $question->options()->delete();
        }

        // Deleta questões
        $exam->questions()->delete();

        // Deleta prova
        $exam->delete();

        return response()->json([
            'message' => 'Exam deleted successfully'
        ]);
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

        $exam = $this->examService->update($id, $validated);

        return response()->json($exam);
    }
}