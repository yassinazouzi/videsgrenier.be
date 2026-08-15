<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GaleriePhoto extends Model
{
    protected $table = 'galerie_photos';

    public $timestamps = false;

    protected $fillable = ['galerie_id', 'url', 'alt', 'ordre'];

    /** Extensions considérées comme des vidéos dans une galerie. */
    public const EXTENSIONS_VIDEO = ['mp4', 'webm', 'mov'];

    public function galerie()
    {
        return $this->belongsTo(Galerie::class);
    }

    /**
     * Le type est déduit de l'extension plutôt que stocké en base : évite
     * une migration à exécuter manuellement en production (l'hébergement
     * mutualisé n'a pas d'accès SSH pour lancer `artisan migrate`).
     */
    public function estVideo(): bool
    {
        return in_array(
            Str::lower(pathinfo($this->url, PATHINFO_EXTENSION)),
            self::EXTENSIONS_VIDEO,
            true
        );
    }

    public function estImage(): bool
    {
        return ! $this->estVideo();
    }

    /** Ne renvoie que les images — utilisé par le slider du hero. */
    public function scopeImagesSeules($query)
    {
        foreach (self::EXTENSIONS_VIDEO as $extension) {
            $query->where('url', 'not like', '%.'.$extension);
        }

        return $query;
    }
}
