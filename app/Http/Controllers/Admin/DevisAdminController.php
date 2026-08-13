<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use App\Models\Devis;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DevisAdminController extends Controller
{
    public function index(Request $request)
    {
        $devis = Devis::query()
            ->when($request->filled('statut'), fn ($q) => $q->where('statut', $request->string('statut')))
            ->when($request->filled('commune'), fn ($q) => $q->where('commune', $request->string('commune')))
            ->when($request->filled('recherche'), function ($q) use ($request) {
                $terme = '%'.$request->string('recherche').'%';
                $q->where(fn ($sq) => $sq->where('nom', 'like', $terme)
                    ->orWhere('telephone', 'like', $terme)
                    ->orWhere('email', 'like', $terme));
            })
            ->latest('cree_le')
            ->paginate(25)
            ->withQueryString();

        return view('admin.devis.index', [
            'devis' => $devis,
            'communes' => Commune::actives()->get(),
        ]);
    }

    public function show(Devis $devi)
    {
        return view('admin.devis.show', ['devis' => $devi]);
    }

    public function update(Request $request, Devis $devi)
    {
        $donnees = $request->validate([
            'statut' => ['required', 'in:'.implode(',', Devis::STATUTS)],
            'montant_devis' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'note_interne' => ['nullable', 'string', 'max:5000'],
        ]);

        $devi->update($donnees);

        return redirect()->route('admin.devis.show', $devi)->with('succes', 'Demande mise à jour.');
    }

    public function destroy(Devis $devi)
    {
        $devi->delete();

        return redirect()->route('admin.devis.index')->with('succes', 'Demande supprimée.');
    }

    public function export(Request $request): StreamedResponse
    {
        $nomFichier = 'devis-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($request) {
            $sortie = fopen('php://output', 'w');
            // BOM UTF-8 : sans lui Excel casse les accents des communes et des noms.
            fwrite($sortie, "\xEF\xBB\xBF");
            fputcsv($sortie, [
                'ID', 'Reçue le', 'Nom', 'Téléphone', 'E-mail', 'Prestation',
                'Commune', 'Volume', 'Canal', 'Statut', 'Montant', 'Source', 'Message', 'Note interne',
            ], ';');

            Devis::query()
                ->when($request->filled('statut'), fn ($q) => $q->where('statut', $request->string('statut')))
                ->latest('cree_le')
                ->chunk(500, function ($lignes) use ($sortie) {
                    foreach ($lignes as $d) {
                        fputcsv($sortie, [
                            $d->id,
                            $d->cree_le?->format('d/m/Y H:i'),
                            $d->nom,
                            $d->telephone,
                            $d->email,
                            $d->prestation,
                            $d->commune,
                            $d->volume_estime,
                            $d->canal,
                            $d->statut,
                            $d->montant_devis,
                            $d->source,
                            $d->message,
                            $d->note_interne,
                        ], ';');
                    }
                });

            fclose($sortie);
        }, $nomFichier, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
