<?php

use App\Http\Controllers\Admin\ArticleAdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CommuneAdminController;
use App\Http\Controllers\Admin\DevisAdminController;
use App\Http\Controllers\Admin\RealisationAdminController;
use App\Http\Controllers\Admin\ReglageController;
use App\Http\Controllers\Admin\ServiceAdminController;
use App\Http\Controllers\Admin\TemoignageAdminController;
use App\Http\Controllers\Admin\GalerieAdminController;
use App\Http\Controllers\Admin\TableauBordController;
use App\Http\Controllers\Admin\UtilisateurController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\GalerieController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\FichierController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RealisationController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Site public
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'accueil'])->name('accueil');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/debarras/{commune}', [CommuneController::class, 'show'])->name('commune.show');

Route::get('/realisations', [RealisationController::class, 'index'])->name('realisations.index');
Route::get('/realisations/{realisation}', [RealisationController::class, 'show'])->name('realisations.show');

Route::get('/galerie', [GalerieController::class, 'index'])->name('galeries.index');
Route::get('/galerie/{galerie}', [GalerieController::class, 'show'])->name('galeries.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{article}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/devis', [DevisController::class, 'form'])->name('devis.form');
Route::post('/devis', [DevisController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('devis.store');
Route::get('/devis/merci', [DevisController::class, 'merci'])->name('devis.merci');

Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/a-propos', 'pages.a-propos')->name('a-propos');
Route::view('/mentions-legales', 'pages.mentions-legales')->name('mentions-legales');
Route::view('/confidentialite', 'pages.confidentialite')->name('confidentialite');

// Sert les fichiers uploadés faute de lien symbolique storage (voir
// FichierController). Ne se déclenche que si aucun fichier statique ne
// répond déjà à cette URL.
Route::get('/storage/{chemin}', FichierController::class)
    ->where('chemin', '.*')
    ->name('storage.fichier');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
Route::get('/llms.txt', [SeoController::class, 'llms'])->name('llms');

/*
|--------------------------------------------------------------------------
| Back-office
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'form'])->name('login');
        Route::post('login', [AuthController::class, 'login'])
            ->middleware('throttle:10,1')
            ->name('login.post');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/', TableauBordController::class)->name('tableau-bord');

        Route::get('devis/export', [DevisAdminController::class, 'export'])->name('devis.export');
        Route::get('devis', [DevisAdminController::class, 'index'])->name('devis.index');
        Route::get('devis/{devi}', [DevisAdminController::class, 'show'])->name('devis.show');
        Route::put('devis/{devi}', [DevisAdminController::class, 'update'])->name('devis.update');
        Route::delete('devis/{devi}', [DevisAdminController::class, 'destroy'])->name('devis.destroy');

        Route::resource('services', ServiceAdminController::class)->except('show');
        Route::resource('communes', CommuneAdminController::class)->only(['index', 'edit', 'update']);
        Route::resource('realisations', RealisationAdminController::class)->except('show');
        Route::resource('temoignages', TemoignageAdminController::class)->except('show');
        Route::resource('articles', ArticleAdminController::class)->except('show');

        // parameters() est indispensable ici : Laravel singularise « galeries »
        // en « galery » (règle anglaise -ies → -y), ce qui ne correspond pas au
        // paramètre $galerie du contrôleur. Sans ça, le liage de route échoue
        // en silence et l'écran d'édition affiche une galerie vide.
        Route::resource('galeries', GalerieAdminController::class)
            ->parameters(['galeries' => 'galerie'])
            ->except('show');
        Route::post('galeries/{galerie}/photos', [GalerieAdminController::class, 'ajouterPhotos'])->name('galeries.photos.ajouter');
        Route::put('galeries/{galerie}/photos', [GalerieAdminController::class, 'majPhotos'])->name('galeries.photos.maj');
        Route::delete('galeries/{galerie}/photos/{photo}', [GalerieAdminController::class, 'supprimerPhoto'])->name('galeries.photos.supprimer');

        // Gestion des comptes : réservée au rôle super_admin.
        Route::middleware('super_admin')->group(function () {
            Route::resource('utilisateurs', UtilisateurController::class)->except('show');
        });

        Route::get('reglages', [ReglageController::class, 'edit'])->name('reglages');
        Route::put('reglages', [ReglageController::class, 'update'])->name('reglages.update');
        Route::get('reglages/slider-photos', [ReglageController::class, 'sliderPhotos'])->name('reglages.slider-photos');
    });
});
