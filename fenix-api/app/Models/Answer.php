<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'attempt_id',
        'question_id',
        'selected_option_id',
    ];

    public function attempt()
    {
        return $this->belongsTo(Attempt::class);
    }
}