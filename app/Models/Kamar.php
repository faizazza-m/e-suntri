<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';
    public $timestamps = false;
    protected $fillable = ['nama', 'kapasitas', 'gedung', 'lantai'];

    public function penghuni()
    {
        return $this->hasMany(PenghuniKamar::class, 'kamar_id');
    }
}
