<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbBatch extends Model
{
    protected $fillable = [
        'name',
        'unit',
        'start_date',
        'end_date',
        'quota',
        'registration_link',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean',
    ];

    public function students()
    {
        return $this->hasMany(PpdbStudent::class, 'batch_id');
    }
}
