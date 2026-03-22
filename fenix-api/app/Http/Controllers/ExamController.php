<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExamService;

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
}