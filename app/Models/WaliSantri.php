<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaliSantri extends Model
{
    protected $table = 'wali_santri';
    public $timestamps = false;
    
    protected $fillable = [
        'user_id', 'santri_id', 'nama', 'hubungan', 'phone', 
        'email', 'alamat', 'pekerjaan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
