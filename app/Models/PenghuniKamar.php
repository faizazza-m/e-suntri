<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenghuniKamar extends Model
{
    use HasFactory;

    protected $table = 'penghuni_kamar';
    public $timestamps = false;
    protected $fillable = ['santri_id', 'kamar_id', 'tanggal_masuk'];

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }
}
