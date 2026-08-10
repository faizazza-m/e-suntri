<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setoran extends Model
{
    protected $table = 'setoran';
    public $timestamps = true;
    const UPDATED_AT = null; // Schema only has created_at

    protected $fillable = [
        'santri_id', 'musyrif_id', 'tanggal', 'jenis', 'surah', 'juz',
        'ayat_dari', 'ayat_sampai', 'nilai', 'catatan'
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function musyrif()
    {
        return $this->belongsTo(User::class, 'musyrif_id');
    }
}
