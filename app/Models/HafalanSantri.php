<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HafalanSantri extends Model
{
    protected $table = 'hafalan_santri';
    public $timestamps = true;
    const CREATED_AT = null; // Scheme only has updated_at

    protected $fillable = [
        'santri_id', 'juz_selesai', 'target_juz', 'status'
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
