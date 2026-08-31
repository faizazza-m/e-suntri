<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiAkademik extends Model
{
    use HasFactory;

    protected $table = 'nilai_akademik';
    public $timestamps = false;
    protected $fillable = ['santri_id', 'mapel_id', 'semester', 'tahun_ajaran', 'nilai_harian', 'nilai_uas', 'nilai_akhir', 'predikat', 'created_at'];

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }
}
