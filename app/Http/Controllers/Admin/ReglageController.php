<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PageController;
use App\Models\Galerie;
use App\Models\Reglage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReglageController extends Controller
{
    public function edit()
    {
        return view('admin.reglages', ['reglages' => Reglage::tous()]);
    }

    public function update(Request $request)
    {
        $donnees = $request->validate([
            'whatsapp_actif' => ['nullable', 'boolean'],
            'whatsapp_numero' => ['nullable', 'string', 'regex:/^[0-9]{8,15}$/'],
            'whatsapp_message' => ['nullable', 'string', 'max:300'],
            'whatsapp_infobulle' => ['nullable', 'string', 'max:80'],
            'whatsapp_horaires' => ['nullable', 'string', 'max:80'],
            'telephone_public' => ['nullable', 'string', 'max:30'],
            'email_devis' => ['nullable', 'email', 'max:190'],
            'site_titre' => ['nullable', 'string', 'max:120'],
            'ga_id' => ['nullable', 'string', 'max:40'],
            'hero_video' => ['nullable', 'string', 'max:500'],
            'hero_video_fichier' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:51200'],
            'hero_video_supprimer' => ['nullable', 'boolean'],
            'facebook_url' => ['nullable', 'url', 'max:255', 'starts_with:https://www.facebook.com/,https://facebook.com/,https://fb.com/'],
            'instagram_url' => ['nullable', 'url', 'max:255', 'starts_with:https://www.instagram.com/,https://instagram.com/'],
            'tiktok_url' => ['nullable', 'url', 'max:255', 'starts_with:https://www.tiktok.com/,https://tiktok.com/'],
        ], [
            'whatsapp_numero.regex' => 'Le numéro WhatsApp doit être au format international sans « + » ni espace (ex. 32491644913).',
            'hero_video_fichier.mimetypes' => 'La vidéo doit être au format MP4, WebM ou MOV.',
            'hero_video_fichier.max' => 'La vidéo dépasse 50 Mo. Compressez-la ou utilisez un lien YouTube à la place.',
            'facebook_url.starts_with' => 'Le lien doit pointer vers facebook.com.',
            'instagram_url.starts_with' => 'Le lien doit pointer vers instagram.com.',
            'tiktok_url.starts_with' => 'Le lien doit pointer vers tiktok.com.',
        ]);

        $donnees['whatsapp_actif'] = $request->boolean('whatsapp_actif') ? '1' : '0';
        $donnees['hero_video'] = $this->resoudreHeroVideo($request);
        unset($donnees['hero_video_fichier'], $donnees['hero_video_supprimer']);

        foreach ($donnees as $cle => $valeur) {
            Reglage::definir($cle, (string) $valeur);
        }

        return redirect()->route('admin.reglages')->with('succes', 'Réglages enregistrés.');
    }

    /**
     * Priorité : suppression demandée > nouveau fichier uploadé > URL saisie
     * (YouTube ou lien direct). Un fichier précédemment uploadé est nettoyé
     * du disque dès qu'il est remplacé ou supprimé.
     */
    private function resoudreHeroVideo(Request $request): string
    {
        $ancienne = Reglage::get('hero_video', '');

        if ($request->boolean('hero_video_supprimer')) {
            $this->supprimerFichierHeroVideo($ancienne);

            return '';
        }

        if ($request->hasFile('hero_video_fichier')) {
            $this->supprimerFichierHeroVideo($ancienne);
            $nom = 'hero-'.Str::random(10).'.'.$request->file('hero_video_fichier')->extension();
            $request->file('hero_video_fichier')->storeAs('hero', $nom, 'public');

            return 'storage/hero/'.$nom;
        }

        return trim((string) $request->input('hero_video', ''));
    }

    private function supprimerFichierHeroVideo(?string $chemin): void
    {
        if ($chemin && str_starts_with($chemin, 'storage/hero/')) {
            Storage::disk('public')->delete(Str::after($chemin, 'storage/'));
        }
    }

    /**
     * Raccourci depuis les réglages vers la galerie interne réservée au
     * slider du hero — crée la galerie au premier accès puis réutilise
     * l'écran d'édition de galerie déjà existant (upload multiple, ordre,
     * alt, suppression).
     */
    public function sliderPhotos()
    {
        $galerie = Galerie::firstOrCreate(
            ['slug' => PageController::GALERIE_HERO_SLUG],
            ['titre' => 'Fond de l\'accueil (slider)', 'publie' => false]
        );

        return redirect()->route('admin.galeries.edit', $galerie);
    }
}
