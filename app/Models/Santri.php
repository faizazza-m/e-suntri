<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    protected $table = 'santri';
    public $timestamps = true;
    const UPDATED_AT = null; // No updated_at in schema
    
    protected $fillable = [
        'user_id', 'nis', 'nama', 'jenis_kelamin', 'tanggal_lahir', 
        'asal_sekolah', 'kelas_id', 'halaqoh_id', 'tahun_masuk', 
        'status', 'foto'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function halaqoh()
    {
        return $this->belongsTo(Halaqoh::class);
    }

    public function wali()
    {
        return $this->hasMany(WaliSantri::class);
    }

    public function hafalan()
    {
        return $this->hasOne(HafalanSantri::class);
    }

    public function setoran()
    {
        return $this->hasMany(Setoran::class);
    }

    public function nilaiAkademik()
    {
        return $this->hasMany(NilaiAkademik::class);
    }

    public function kamar()
    {
        return $this->hasOne(PenghuniKamar::class);
    }

    public function kehadirans()
    {
        return $this->hasMany(Kehadiran::class);
    }
}
