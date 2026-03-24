<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attempt;
use App\Models\Exam;

class DashboardSeeder extends Seeder
{
    public function run(): void
    {
        Attempt::truncate();

        $exams = Exam::with('questions')->get();

        if ($exams->isEmpty()) {
            $this->command->warn('Nenhuma prova encontrada.');
            return;
        }

        foreach ($exams as $exam) {
            $students = collect(range(1, 20))
                ->shuffle()
                ->take(rand(5, 15));

            $totalQuestions = $exam->questions->count();
            
            if ($totalQuestions === 0) continue; 

            foreach ($students as $studentId) {
                $score = rand(0, $totalQuestions);
                
                $percentage = ($score / $totalQuestions) * 100;

                Attempt::factory()->create([
                    'student_id' => $studentId,
                    'exam_id' => $exam->id,
                    'score' => $score,
                    'percentage' => round($percentage, 2),
                    'created_at' => now()->subDays(rand(0, 10)),
                ]);
            }
        }
    }
}