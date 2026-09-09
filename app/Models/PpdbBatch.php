<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbBatch extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'quota',
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
