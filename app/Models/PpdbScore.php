<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbScore extends Model
{
    protected $fillable = [
        'student_id',
        'quran_test',
        'written_test',
        'interview_test',
        'total_score',
        'notes',
    ];

    public function student()
    {
        return $this->belongsTo(PpdbStudent::class, 'student_id');
    }
}
