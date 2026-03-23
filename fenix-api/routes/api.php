<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AttemptController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SwaggerTestController;

// Exam
Route::get('/exams', [ExamController::class, 'index']);
Route::post('/exams', [ExamController::class, 'store']);
Route::get('/exams/{id}', [ExamController::class, 'show']);
Route::get('/exams/{id}/edit', [ExamController::class, 'showWithAnswers']);
Route::put('/exams/{id}', [ExamController::class, 'update']);
Route::delete('/exams/{id}', [ExamController::class, 'destroy']);
Route::get('/debug', [ExamController::class, 'debug']);

// Questions
Route::post('/questions', [QuestionController::class, 'store']);

// Attempt
Route::post('/exams/{examId}/submit', [AttemptController::class, 'submit']);

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/exams/{examId}/stats', [DashboardController::class, 'examStats']);