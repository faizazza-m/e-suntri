<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    public $timestamps = false;
    protected $fillable = ['tagihan_id','santri_id','tanggal_bayar','nominal_bayar','metode','no_invoice','dikonfirmasi_oleh','catatan'];

    public function santri() { return $this->belongsTo(Santri::class); }
    public function tagihan() { return $this->belongsTo(Tagihan::class); }
}
