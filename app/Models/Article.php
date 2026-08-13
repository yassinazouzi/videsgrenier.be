<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';

    const CREATED_AT = 'cree_le';
    const UPDATED_AT = null;

    protected $fillable = [
        'slug', 'titre', 'extrait', 'contenu', 'image_une', 'categorie',
        'statut', 'meta_title', 'meta_description', 'publie_le',
    ];

    protected $casts = [
        'publie_le' => 'datetime',
        'cree_le' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublies($query)
    {
        return $query->where('statut', 'publie')
            ->where('publie_le', '<=', now())
            ->latest('publie_le');
    }
}
