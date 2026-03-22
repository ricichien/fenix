<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'title',
        'description',
        'created_by',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}