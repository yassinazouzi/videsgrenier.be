<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Temoignage extends Model
{
    protected $table = 'temoignages';

    public $timestamps = false;

    protected $fillable = ['auteur', 'commune', 'note', 'texte', 'publie', 'ordre'];

    protected $casts = ['publie' => 'boolean'];

    public function scopePublies($query)
    {
        return $query->where('publie', true)->orderBy('ordre');
    }
}
