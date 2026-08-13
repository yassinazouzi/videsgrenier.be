<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Commune;
use App\Models\Galerie;
use App\Models\Realisation;
use App\Models\Reglage;
use App\Models\Service;

class SeoController extends Controller
{
    public function sitemap()
    {
        $urls = [
            ['loc' => route('accueil'), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => route('services.index'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('realisations.index'), 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => route('devis.form'), 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['loc' => route('contact'), 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['loc' => route('a-propos'), 'priority' => '0.5', 'changefreq' => 'yearly'],
        ];

        foreach (Service::actifs()->get() as $service) {
            $urls[] = ['loc' => route('services.show', $service), 'priority' => '0.8', 'changefreq' => 'monthly'];
        }

        // Les 19 pages communes sont le cœur du SEO local : priorité haute.
        foreach (Commune::actives()->get() as $commune) {
            $urls[] = ['loc' => route('commune.show', $commune), 'priority' => '0.9', 'changefreq' => 'monthly'];
        }

        foreach (Realisation::publiees()->get() as $realisation) {
            $urls[] = [
                'loc' => route('realisations.show', $realisation),
                'priority' => '0.6',
                'changefreq' => 'yearly',
                'lastmod' => $realisation->cree_le?->toAtomString(),
            ];
        }

        foreach (Galerie::publiees()->get() as $galerie) {
            $urls[] = ['loc' => route('galeries.show', $galerie), 'priority' => '0.5', 'changefreq' => 'monthly'];
        }

        foreach (Article::publies()->get() as $article) {
            $urls[] = [
                'loc' => route('blog.show', $article),
                'priority' => '0.6',
                'changefreq' => 'monthly',
                'lastmod' => $article->publie_le?->toAtomString(),
            ];
        }

        return response()
            ->view('seo.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots()
    {
        $lignes = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /devis/merci',
            '',
            // GEO : on autorise explicitement les crawlers des moteurs génératifs.
            'User-agent: GPTBot',
            'Allow: /',
            '',
            'User-agent: PerplexityBot',
            'Allow: /',
            '',
            'User-agent: Google-Extended',
            'Allow: /',
            '',
            'User-agent: ClaudeBot',
            'Allow: /',
            '',
            'Sitemap: '.url('sitemap.xml'),
            '',
        ];

        return response(implode("\n", $lignes))
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function llms()
    {
        return response()
            ->view('seo.llms', [
                'communes' => Commune::actives()->get(),
                'services' => Service::actifs()->get(),
                'reglages' => Reglage::tous(),
            ])
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
