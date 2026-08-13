<?php

namespace App\Http\Controllers;

use App\Http\Requests\DevisRequest;
use App\Mail\AccuseReceptionDevis;
use App\Mail\NouveauDevis;
use App\Models\Commune;
use App\Models\Devis;
use App\Models\Reglage;
use App\Models\Service;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DevisController extends Controller
{
    public function form()
    {
        return view('pages.devis', [
            'services' => Service::actifs()->get(),
            'communes' => Commune::actives()->get(),
        ]);
    }

    public function store(DevisRequest $request)
    {
        $devis = Devis::create($request->safe()->except('societe') + [
            'canal' => 'formulaire',
            'statut' => 'nouveau',
        ]);

        // La queue est en sync sur mutualisé : une panne SMTP ne doit pas faire perdre le lead déjà enregistré.
        try {
            if ($destinataire = Reglage::get('email_devis')) {
                Mail::to($destinataire)->send(new NouveauDevis($devis));
            }

            if ($devis->email) {
                Mail::to($devis->email)->send(new AccuseReceptionDevis($devis));
            }
        } catch (\Throwable $e) {
            Log::error('Envoi des e-mails de devis échoué', ['devis_id' => $devis->id, 'erreur' => $e->getMessage()]);
        }

        return redirect()->route('devis.merci')->with('devis_source', $devis->source ?: 'formulaire');
    }

    public function merci()
    {
        return view('pages.devis-merci');
    }
}
