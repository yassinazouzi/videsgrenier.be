<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    protected $table = 'devis';

    const CREATED_AT = 'cree_le';
    const UPDATED_AT = 'maj_le';

    protected $fillable = [
        'nom', 'telephone', 'email', 'prestation', 'commune', 'message',
        'volume_estime', 'source', 'canal', 'statut', 'montant_devis', 'note_interne',
    ];

    protected $casts = [
        'montant_devis' => 'decimal:2',
        'cree_le' => 'datetime',
        'maj_le' => 'datetime',
    ];

    public const STATUTS = ['nouveau', 'contacte', 'devis_envoye', 'gagne', 'perdu'];

    public function scopeNouveaux($query)
    {
        return $query->where('statut', 'nouveau');
    }
}
