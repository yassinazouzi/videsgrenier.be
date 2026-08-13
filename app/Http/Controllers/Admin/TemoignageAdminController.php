<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use App\Models\Temoignage;
use Illuminate\Http\Request;

class TemoignageAdminController extends Controller
{
    public function index()
    {
        return view('admin.temoignages.index', [
            'temoignages' => Temoignage::orderBy('ordre')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.temoignages.form', [
            'temoignage' => new Temoignage(['note' => 5]),
            'communes' => Commune::actives()->get(),
        ]);
    }

    public function store(Request $request)
    {
        Temoignage::create($this->valider($request));

        return redirect()->route('admin.temoignages.index')->with('succes', 'Témoignage créé.');
    }

    public function edit(Temoignage $temoignage)
    {
        return view('admin.temoignages.form', [
            'temoignage' => $temoignage,
            'communes' => Commune::actives()->get(),
        ]);
    }

    public function update(Request $request, Temoignage $temoignage)
    {
        $temoignage->update($this->valider($request));

        return redirect()->route('admin.temoignages.index')->with('succes', 'Témoignage mis à jour.');
    }

    public function destroy(Temoignage $temoignage)
    {
        $temoignage->delete();

        return redirect()->route('admin.temoignages.index')->with('succes', 'Témoignage supprimé.');
    }

    private function valider(Request $request): array
    {
        $donnees = $request->validate([
            'auteur' => ['required', 'string', 'max:120'],
            'commune' => ['nullable', 'string', 'max:80'],
            'note' => ['required', 'integer', 'min:1', 'max:5'],
            'texte' => ['required', 'string', 'max:2000'],
            'ordre' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'publie' => ['nullable', 'boolean'],
        ]);

        $donnees['ordre'] = $donnees['ordre'] ?? 0;
        $donnees['publie'] = $request->boolean('publie');

        return $donnees;
    }
}
