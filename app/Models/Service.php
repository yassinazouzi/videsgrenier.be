<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    public $timestamps = false;

    protected $fillable = [
        'slug', 'titre', 'icone', 'extrait', 'contenu', 'ordre', 'actif',
        'meta_title', 'meta_description',
    ];

    protected $casts = ['actif' => 'boolean'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActifs($query)
    {
        return $query->where('actif', true)->orderBy('ordre');
    }
}
