<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    protected $table = 'perizinan';
    protected $fillable = ['santri_id','jenis','tanggal_mulai','tanggal_selesai','alasan','status','disetujui_oleh','catatan_admin'];

    public function santri() { return $this->belongsTo(Santri::class); }
    public function disetujuiOleh() { return $this->belongsTo(User::class, 'disetujui_oleh'); }
}
