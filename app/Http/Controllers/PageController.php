<?php

namespace App\Http\Controllers;

use App\Models\Commune;
use App\Models\Galerie;
use App\Models\Realisation;
use App\Models\Reglage;
use App\Models\Service;
use App\Models\Temoignage;

class PageController extends Controller
{
    /** Slug réservé de la galerie interne utilisée pour le slider manuel du hero. */
    public const GALERIE_HERO_SLUG = 'hero-accueil';

    public function accueil()
    {
        return view('pages.accueil', [
            'services' => Service::actifs()->get(),
            'communes' => Commune::actives()->get(),
            'realisations' => Realisation::publiees()->limit(3)->get(),
            'temoignages' => Temoignage::publies()->limit(3)->get(),
            'faq' => config('site.faq'),
            'heroPhotos' => $this->heroPhotos(),
            'heroVideo' => $this->heroVideo(),
        ]);
    }

    /**
     * Photos du fond animé du hero, par ordre de priorité :
     * 1. Slider uploadé manuellement depuis l'admin (galerie interne "hero-accueil")
     * 2. À défaut, photos "après" des réalisations publiées
     * Tant qu'aucune des deux n'existe, la vue retombe sur un dégradé animé —
     * jamais de photo fictive.
     */
    private function heroPhotos(): array
    {
        $manuelles = Galerie::where('slug', self::GALERIE_HERO_SLUG)
            ->first()
            ?->photos()
            ->orderBy('ordre')
            ->pluck('url')
            ->all();

        if (! empty($manuelles)) {
            return array_slice($manuelles, 0, 6);
        }

        return Realisation::publiees()
            ->latest('cree_le')
            ->get()
            ->map(fn ($r) => $r->photo_apres ?: $r->couverture)
            ->filter()
            ->take(4)
            ->values()
            ->all();
    }

    /**
     * Analyse le réglage hero_video : URL YouTube (rendue en iframe) ou
     * fichier vidéo direct — uploadé depuis l'admin ou URL externe (rendu
     * en <video>). Retourne null si aucune vidéo n'est configurée.
     */
    private function heroVideo(): ?array
    {
        $valeur = trim((string) Reglage::get('hero_video', ''));
        if ($valeur === '') {
            return null;
        }

        if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{11})~i', $valeur, $m)) {
            return ['type' => 'youtube', 'id' => $m[1]];
        }

        return [
            'type' => 'fichier',
            'url' => preg_match('~^https?://~i', $valeur) ? $valeur : asset($valeur),
        ];
    }
}
