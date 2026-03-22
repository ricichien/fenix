<?php

namespace App\Services;

use App\Models\Exam;

class ExamService
{
    public function create(array $data): Exam
    {
        return Exam::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'created_by' => $data['created_by'],
        ]);
    }

    public function getAll()
    {
        return Exam::all();
    }
}