<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceAdminController extends Controller
{
    public function index()
    {
        return view('admin.services.index', [
            'services' => Service::orderBy('ordre')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.services.form', ['service' => new Service()]);
    }

    public function store(Request $request)
    {
        $service = Service::create($this->valider($request));

        return redirect()->route('admin.services.index')->with('succes', "Service « {$service->titre} » créé.");
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', ['service' => $service]);
    }

    public function update(Request $request, Service $service)
    {
        $service->update($this->valider($request, $service));

        return redirect()->route('admin.services.index')->with('succes', 'Service mis à jour.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('succes', 'Service supprimé.');
    }

    private function valider(Request $request, ?Service $service = null): array
    {
        $donnees = $request->validate([
            'titre' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:120', 'alpha_dash', Rule::unique('services', 'slug')->ignore($service?->id)],
            'icone' => ['nullable', 'string', 'max:20'],
            'extrait' => ['nullable', 'string', 'max:255'],
            'contenu' => ['nullable', 'string'],
            'ordre' => ['nullable', 'integer', 'min:0', 'max:255'],
            'actif' => ['nullable', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:190'],
            'meta_description' => ['nullable', 'string', 'max:320'],
        ]);

        $donnees['slug'] = ($donnees['slug'] ?? null) ?: Str::slug($donnees['titre']);
        $donnees['ordre'] = $donnees['ordre'] ?? 0;
        $donnees['actif'] = $request->boolean('actif');

        return $donnees;
    }
}
