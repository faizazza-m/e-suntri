<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'tagihan';
    public $timestamps = false;
    protected $fillable = ['santri_id','jenis_id','bulan','tahun','nominal','jatuh_tempo','status'];

    public function santri() { return $this->belongsTo(Santri::class); }
    public function jenis() { return $this->belongsTo(JenisTagihan::class, 'jenis_id'); }
    public function pembayaran() { return $this->hasMany(Pembayaran::class); }
}
