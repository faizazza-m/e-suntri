<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';
    public $timestamps = false;
    protected $fillable = ['nama', 'kode', 'guru_id'];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function jadwal()
    {
        return $this->hasMany(JadwalPelajaran::class, 'mapel_id');
    }

    public function nilai()
    {
        return $this->hasMany(NilaiAkademik::class, 'mapel_id');
    }
}
