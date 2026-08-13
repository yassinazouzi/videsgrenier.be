<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Realisation extends Model
{
    protected $table = 'realisations';

    const CREATED_AT = 'cree_le';
    const UPDATED_AT = null;

    protected $fillable = [
        'slug', 'titre', 'commune', 'type_presta', 'description', 'duree',
        'photo_avant', 'photo_apres', 'couverture', 'publie',
    ];

    protected $casts = [
        'publie' => 'boolean',
        'cree_le' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePubliees($query)
    {
        return $query->where('publie', true)->latest('cree_le');
    }
}
