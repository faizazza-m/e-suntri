<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Halaqoh extends Model
{
    protected $table = 'halaqoh';
    public $timestamps = false;
    protected $fillable = ['nama', 'musyrif_id'];

    public function musyrif()
    {
        return $this->belongsTo(User::class, 'musyrif_id');
    }

    public function santri()
    {
        return $this->hasMany(Santri::class);
    }
}
