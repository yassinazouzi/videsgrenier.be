<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galerie extends Model
{
    protected $table = 'galeries';

    const CREATED_AT = 'cree_le';
    const UPDATED_AT = null;

    protected $fillable = ['titre', 'slug', 'description', 'couverture', 'publie'];

    protected $casts = [
        'publie' => 'boolean',
        'cree_le' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function photos()
    {
        return $this->hasMany(GaleriePhoto::class)->orderBy('ordre');
    }

    public function scopePubliees($query)
    {
        return $query->where('publie', true)->latest('cree_le');
    }
}
