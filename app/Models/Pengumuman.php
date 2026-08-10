<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';
    public $timestamps = false;
    protected $fillable = ['judul','isi','target','dibuat_oleh','is_pinned','published_at'];
    protected $casts = ['published_at' => 'datetime', 'is_pinned' => 'boolean'];

    public function pembuat()
    {
        return $this->belongsTo(\App\Models\User::class, 'dibuat_oleh');
    }
}
