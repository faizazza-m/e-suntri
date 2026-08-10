<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    public $timestamps = false;
    protected $fillable = ['nama', 'julukan', 'tingkat', 'wali_kelas_id', 'kapasitas'];

    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function santri()
    {
        return $this->hasMany(Santri::class);
    }
}
