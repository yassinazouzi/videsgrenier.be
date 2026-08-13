<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use Illuminate\Http\Request;

class CommuneAdminController extends Controller
{
    public function index()
    {
        return view('admin.communes.index', [
            'communes' => Commune::orderBy('nom')->get(),
        ]);
    }

    public function edit(Commune $commune)
    {
        return view('admin.communes.form', ['commune' => $commune]);
    }

    public function update(Request $request, Commune $commune)
    {
        $donnees = $request->validate([
            'intro' => ['nullable', 'string', 'max:20000'],
            'meta_title' => ['nullable', 'string', 'max:190'],
            'meta_description' => ['nullable', 'string', 'max:320'],
            'actif' => ['nullable', 'boolean'],
        ]);

        $donnees['actif'] = $request->boolean('actif');
        $commune->update($donnees);

        return redirect()->route('admin.communes.index')
            ->with('succes', "Page « {$commune->nom} » mise à jour.");
    }
}
