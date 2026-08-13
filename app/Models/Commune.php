<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    protected $table = 'communes';

    public $timestamps = false;

    protected $fillable = [
        'nom', 'slug', 'code_postal', 'intro', 'actif',
        'meta_title', 'meta_description',
    ];

    protected $casts = ['actif' => 'boolean'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActives($query)
    {
        return $query->where('actif', true)->orderBy('nom');
    }

    public function realisations()
    {
        return $this->hasMany(Realisation::class, 'commune', 'nom');
    }
}
