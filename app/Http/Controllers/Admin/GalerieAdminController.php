<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galerie;
use App\Models\GaleriePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GalerieAdminController extends Controller
{
    public function index()
    {
        return view('admin.galeries.index', [
            'galeries' => Galerie::withCount('photos')->latest('cree_le')->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.galeries.form', ['galerie' => new Galerie()]);
    }

    public function store(Request $request)
    {
        $galerie = Galerie::create($this->valider($request));

        return redirect()->route('admin.galeries.edit', $galerie)
            ->with('succes', 'Galerie créée — vous pouvez maintenant ajouter des photos.');
    }

    public function edit(Galerie $galerie)
    {
        return view('admin.galeries.form', ['galerie' => $galerie->load('photos')]);
    }

    public function update(Request $request, Galerie $galerie)
    {
        $galerie->update($this->valider($request, $galerie));

        return redirect()->route('admin.galeries.edit', $galerie)->with('succes', 'Galerie mise à jour.');
    }

    public function destroy(Galerie $galerie)
    {
        foreach ($galerie->photos as $photo) {
            $this->supprimerFichier($photo->url);
        }

        $this->supprimerFichier($galerie->couverture);
        $galerie->delete();

        return redirect()->route('admin.galeries.index')->with('succes', 'Galerie supprimée.');
    }

    /**
     * Upload multiple : chaque fichier est renommé à partir du slug de la galerie
     * et placé en fin de liste.
     */
    public function ajouterPhotos(Request $request, Galerie $galerie)
    {
        $request->validate([
            'photos' => ['required', 'array', 'max:30'],
            // Images et vidéos acceptées dans une même galerie. La limite de
            // taille est plus haute pour les vidéos, forcément plus lourdes.
            'photos.*' => [
                'file',
                'mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime',
                'max:51200',
            ],
        ], [
            'photos.*.mimetypes' => 'Formats acceptés : JPG, PNG, WebP pour les photos, MP4, WebM ou MOV pour les vidéos.',
            'photos.*.max' => 'Chaque fichier doit faire moins de 50 Mo.',
        ]);

        $ordre = (int) $galerie->photos()->max('ordre');

        foreach ($request->file('photos') as $fichier) {
            $nom = $galerie->slug.'-'.Str::random(8).'.'.$fichier->extension();
            $fichier->storeAs('galeries', $nom, 'public');

            $galerie->photos()->create([
                'url' => 'storage/galeries/'.$nom,
                'alt' => $galerie->titre,
                'ordre' => ++$ordre,
            ]);
        }

        return redirect()->route('admin.galeries.edit', $galerie)
            ->with('succes', count($request->file('photos')).' fichier(s) ajouté(s).');
    }

    public function majPhotos(Request $request, Galerie $galerie)
    {
        $donnees = $request->validate([
            'photos' => ['array'],
            'photos.*.alt' => ['nullable', 'string', 'max:190'],
            'photos.*.ordre' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);

        foreach ($donnees['photos'] ?? [] as $id => $champs) {
            $galerie->photos()->whereKey($id)->update([
                'alt' => $champs['alt'] ?? null,
                'ordre' => $champs['ordre'] ?? 0,
            ]);
        }

        return redirect()->route('admin.galeries.edit', $galerie)->with('succes', 'Photos mises à jour.');
    }

    public function supprimerPhoto(Galerie $galerie, GaleriePhoto $photo)
    {
        abort_unless($photo->galerie_id === $galerie->id, 404);

        $this->supprimerFichier($photo->url);
        $photo->delete();

        return redirect()->route('admin.galeries.edit', $galerie)->with('succes', 'Photo supprimée.');
    }

    private function valider(Request $request, ?Galerie $galerie = null): array
    {
        $donnees = $request->validate([
            'titre' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:150', 'alpha_dash', Rule::unique('galeries', 'slug')->ignore($galerie?->id)],
            'description' => ['nullable', 'string', 'max:5000'],
            'publie' => ['nullable', 'boolean'],
            'couverture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $donnees['slug'] = ($donnees['slug'] ?? null) ?: Str::slug($donnees['titre']);
        $donnees['publie'] = $request->boolean('publie');

        if ($request->hasFile('couverture')) {
            $this->supprimerFichier($galerie?->couverture);
            $nom = $donnees['slug'].'-couverture-'.Str::random(6).'.'.$request->file('couverture')->extension();
            $request->file('couverture')->storeAs('galeries', $nom, 'public');
            $donnees['couverture'] = 'storage/galeries/'.$nom;
        } else {
            unset($donnees['couverture']);
        }

        return $donnees;
    }

    private function supprimerFichier(?string $chemin): void
    {
        if ($chemin && str_starts_with($chemin, 'storage/')) {
            Storage::disk('public')->delete(Str::after($chemin, 'storage/'));
        }
    }
}
