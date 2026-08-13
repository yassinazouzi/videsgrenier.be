<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriePhoto extends Model
{
    protected $table = 'galerie_photos';

    public $timestamps = false;

    protected $fillable = ['galerie_id', 'url', 'alt', 'ordre'];

    public function galerie()
    {
        return $this->belongsTo(Galerie::class);
    }
}
