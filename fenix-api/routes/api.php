<?php
// Exam
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamController;
Route::get('/exams', [ExamController::class, 'index']);
Route::post('/exams', [ExamController::class, 'store']);

// Questions
use App\Http\Controllers\QuestionController;
Route::post('/questions', [QuestionController::class, 'store']);

// Attempt
use App\Http\Controllers\AttemptController;
Route::post('/exams/{examId}/submit', [AttemptController::class, 'submit']);

// Dashboard
use App\Http\Controllers\DashboardController;
Route::get('/exams/{examId}/stats', [DashboardController::class, 'examStats']);