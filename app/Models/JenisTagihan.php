<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisTagihan extends Model
{
    protected $table = 'jenis_tagihan';
    public $timestamps = false;
    protected $fillable = ['nama','nominal','periode','keterangan'];

    public function tagihan() { return $this->hasMany(Tagihan::class, 'jenis_id'); }
}
