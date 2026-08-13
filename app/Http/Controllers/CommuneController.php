<?php

namespace App\Http\Controllers;

use App\Models\Commune;
use App\Models\Realisation;
use App\Models\Service;
use App\Models\Temoignage;

class CommuneController extends Controller
{
    public function show(Commune $commune)
    {
        abort_unless($commune->actif, 404);

        return view('pages.commune', [
            'commune' => $commune,
            'services' => Service::actifs()->get(),
            'realisations' => Realisation::publiees()->where('commune', $commune->nom)->limit(2)->get(),
            'temoignages' => Temoignage::publies()->where('commune', $commune->nom)->limit(2)->get(),
            'autresCommunes' => Commune::actives()->where('id', '!=', $commune->id)->get(),
        ]);
    }
}
