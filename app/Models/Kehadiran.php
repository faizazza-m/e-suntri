<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    protected $table = 'kehadiran';
    public $timestamps = false;
    protected $fillable = ['santri_id','tanggal','status','keterangan','dicatat_oleh'];

    public function santri() { return $this->belongsTo(Santri::class); }
}
