<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use App\Models\Realisation;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RealisationAdminController extends Controller
{
    public function index()
    {
        return view('admin.realisations.index', [
            'realisations' => Realisation::latest('cree_le')->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.realisations.form', $this->options() + ['realisation' => new Realisation()]);
    }

    public function store(Request $request)
    {
        Realisation::create($this->valider($request));

        return redirect()->route('admin.realisations.index')->with('succes', 'Réalisation créée.');
    }

    public function edit(Realisation $realisation)
    {
        return view('admin.realisations.form', $this->options() + ['realisation' => $realisation]);
    }

    public function update(Request $request, Realisation $realisation)
    {
        $realisation->update($this->valider($request, $realisation));

        return redirect()->route('admin.realisations.index')->with('succes', 'Réalisation mise à jour.');
    }

    public function destroy(Realisation $realisation)
    {
        foreach (['photo_avant', 'photo_apres', 'couverture'] as $champ) {
            $this->supprimerFichier($realisation->$champ);
        }

        $realisation->delete();

        return redirect()->route('admin.realisations.index')->with('succes', 'Réalisation supprimée.');
    }

    private function options(): array
    {
        return [
            'communes' => Commune::actives()->get(),
            'services' => Service::actifs()->get(),
        ];
    }

    private function valider(Request $request, ?Realisation $realisation = null): array
    {
        $donnees = $request->validate([
            'titre' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:150', 'alpha_dash', Rule::unique('realisations', 'slug')->ignore($realisation?->id)],
            'commune' => ['nullable', 'string', 'max:80'],
            'type_presta' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:5000'],
            'duree' => ['nullable', 'string', 'max:60'],
            'publie' => ['nullable', 'boolean'],
            'photo_avant' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'photo_apres' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $donnees['slug'] = ($donnees['slug'] ?? null) ?: Str::slug($donnees['titre']);
        $donnees['publie'] = $request->boolean('publie');

        foreach (['photo_avant', 'photo_apres'] as $champ) {
            if ($request->hasFile($champ)) {
                $this->supprimerFichier($realisation?->$champ);
                $donnees[$champ] = $this->stocker($request, $champ, $donnees['slug']);
            } else {
                unset($donnees[$champ]);
            }
        }

        return $donnees;
    }

    /**
     * Nom de fichier reconstruit à partir du slug : jamais celui envoyé par le client,
     * et descriptif pour le SEO images (« debarras-appartement-ixelles-avant.jpg »).
     */
    private function stocker(Request $request, string $champ, string $slug): string
    {
        $suffixe = str_replace('photo_', '', $champ);
        $extension = $request->file($champ)->extension();
        $nom = $slug.'-'.$suffixe.'-'.Str::random(6).'.'.$extension;

        $request->file($champ)->storeAs('realisations', $nom, 'public');

        return 'storage/realisations/'.$nom;
    }

    private function supprimerFichier(?string $chemin): void
    {
        if ($chemin && str_starts_with($chemin, 'storage/')) {
            Storage::disk('public')->delete(Str::after($chemin, 'storage/'));
        }
    }
}
