<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbStudent extends Model
{
    protected $fillable = [
        'batch_id',
        'registration_number',
        'nik',
        'nisn',
        'full_name',
        'gender',
        'birth_place',
        'birth_date',
        'previous_school',
        'father_name',
        'mother_name',
        'parent_phone',
        'address',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function batch()
    {
        return $this->belongsTo(PpdbBatch::class, 'batch_id');
    }

    public function documents()
    {
        return $this->hasMany(PpdbDocument::class, 'student_id');
    }

    public function score()
    {
        return $this->hasOne(PpdbScore::class, 'student_id');
    }
}
