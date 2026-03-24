<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'score',
        'percentage',
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}