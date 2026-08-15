<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanGuru extends Model
{
    protected $fillable = [
        'guru_id', 'tanggal', 'kelas', 'mata_pelajaran', 'materi', 'isi_laporan', 'status'
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
