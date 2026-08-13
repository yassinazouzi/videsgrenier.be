<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admins';

    const CREATED_AT = 'cree_le';
    const UPDATED_AT = null;

    protected $fillable = ['nom', 'email', 'mot_de_passe', 'role'];

    protected $hidden = ['mot_de_passe'];

    protected $casts = [
        'mot_de_passe' => 'hashed',
        'cree_le' => 'datetime',
    ];

    public function getAuthPassword(): string
    {
        return $this->mot_de_passe;
    }

    // La table admins (spec §4) n'a pas de colonne remember_token : "rester connecté"
    // s'appuie uniquement sur la durée de session, pas sur un cookie longue durée.
    public function getRememberTokenName(): ?string
    {
        return null;
    }

    public function estSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }
}
