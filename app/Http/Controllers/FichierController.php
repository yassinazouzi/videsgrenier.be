<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

/**
 * Sert les fichiers uploadés (photos de réalisations, galeries, blog,
 * vidéo de fond) depuis storage/app/public.
 *
 * Sur un hébergement mutualisé sans accès SSH, `php artisan storage:link`
 * ne peut pas créer le lien symbolique public/storage → storage/app/public.
 * Cette route prend le relais : elle ne se déclenche que si aucun fichier
 * statique ne répond déjà à l'URL, donc elle n'ajoute aucun coût là où le
 * lien symbolique existe (environnement local, ou serveur avec SSH).
 */
class FichierController extends Controller
{
    public function __invoke(string $chemin)
    {
        // Empêche toute remontée de répertoire hors du dossier de stockage.
        if (str_contains($chemin, '..') || str_starts_with($chemin, '/')) {
            abort(404);
        }

        $disque = Storage::disk('public');

        abort_unless($disque->exists($chemin), 404);

        return $disque->response($chemin, null, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
