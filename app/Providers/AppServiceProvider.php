<?php

namespace App\Providers;

use App\Models\Commune;
use App\Models\Devis;
use App\Models\Reglage;
use App\Models\Service;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Ciblé sur les pages ET le layout : les blocs @push('tete') des pages
        // sont évalués avant le layout et ont besoin des mêmes données.
        View::composer(['layouts.site', 'pages.*'], function ($view) {
            $view->with(once(fn () => [
                'reglages' => Reglage::tous(),
                'servicesFooter' => Service::actifs()->limit(6)->get(),
                'communesFooter' => Commune::actives()->limit(6)->get(),
            ]));
        });

        View::composer('admin.layout', function ($view) {
            $view->with('devisNouveaux', Devis::nouveaux()->count());
        });
    }
}
