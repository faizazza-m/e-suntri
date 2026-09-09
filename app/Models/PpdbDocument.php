<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbDocument extends Model
{
    protected $fillable = [
        'student_id',
        'document_type',
        'file_path',
        'status',
        'notes',
    ];

    public function student()
    {
        return $this->belongsTo(PpdbStudent::class, 'student_id');
    }
}
