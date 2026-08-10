<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekamKesehatan extends Model
{
    protected $table = 'rekam_kesehatan';
    public $timestamps = false; // Only has created_at
    protected $fillable = ['santri_id', 'tanggal', 'keluhan', 'diagnosa', 'tindakan', 'petugas_id', 'dirujuk', 'tempat_rujukan'];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
