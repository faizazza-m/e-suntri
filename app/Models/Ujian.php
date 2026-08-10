<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    use HasFactory;

    protected $table = 'jadwal_ujian';
    public $timestamps = false;
    
    protected $fillable = [
        'judul', 'tipe', 'mapel_id', 'kelas_id', 
        'tanggal', 'jam_mulai', 'jam_selesai', 'keterangan'
    ];

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
