<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Devis;

class TableauBordController extends Controller
{
    public function __invoke()
    {
        $parStatut = Devis::selectRaw('statut, COUNT(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut');

        $traites = ($parStatut['gagne'] ?? 0) + ($parStatut['perdu'] ?? 0);

        return view('admin.tableau-bord', [
            'parStatut' => $parStatut,
            'nouveaux' => $parStatut['nouveau'] ?? 0,
            'enCours' => ($parStatut['contacte'] ?? 0) + ($parStatut['devis_envoye'] ?? 0),
            'aujourdhui' => Devis::whereDate('cree_le', today())->count(),
            'tauxConversion' => $traites > 0 ? round(($parStatut['gagne'] ?? 0) / $traites * 100) : null,
            'caGagne' => Devis::where('statut', 'gagne')->sum('montant_devis'),
            'derniers' => Devis::latest('cree_le')->limit(8)->get(),
            'parJour' => Devis::selectRaw('DATE(cree_le) as jour, COUNT(*) as total')
                ->where('cree_le', '>=', now()->subDays(13)->startOfDay())
                ->groupBy('jour')
                ->orderBy('jour')
                ->pluck('total', 'jour'),
        ]);
    }
}
