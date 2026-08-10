<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi secara massal
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role_id',
        'foto',
        'remember_token',
    ];

    /**
     * Kolom yang disembunyikan dari serialisasi
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast tipe data
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ─── Relationships ──────────────────────────────────
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function santri()
    {
        return $this->hasOne(Santri::class);
    }

    public function waliSantri()
    {
        return $this->hasOne(WaliSantri::class);
    }
}
