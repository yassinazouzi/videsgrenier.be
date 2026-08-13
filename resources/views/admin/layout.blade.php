<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
{{-- Le back-office ne doit jamais être indexé, ni par Google ni par les crawlers IA. --}}
<meta name="robots" content="noindex,nofollow,noarchive">
<title>@yield('titre', 'Administration') — Videsgrenier.be</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&family=IBM+Plex+Mono:wght@500;600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/style.css') }}">
<script>
// Posé avant le rendu du body pour éviter un flash sombre→clair au chargement.
(function () {
  try {
    if (localStorage.getItem('vg_admin_theme') === 'light') {
      document.documentElement.setAttribute('data-theme', 'light');
    }
  } catch (e) {}
})();
</script>
</head>
<body class="bo">

<aside class="bo-sidebar">
  <div class="bo-logo">Videsgrenier<span>.be</span></div>

  <div class="bo-nav-titre">Pilotage</div>
  <a class="bo-lien {{ request()->routeIs('admin.tableau-bord') ? 'actif' : '' }}" href="{{ route('admin.tableau-bord') }}">
    <span>▦</span> Tableau de bord
  </a>
  <a class="bo-lien {{ request()->routeIs('admin.devis.*') ? 'actif' : '' }}" href="{{ route('admin.devis.index') }}">
    <span>✉</span> Demandes de devis
    @if($devisNouveaux > 0)<span class="pastille">{{ $devisNouveaux }}</span>@endif
  </a>

  <div class="bo-nav-titre">Contenu &amp; SEO</div>
  <a class="bo-lien {{ request()->routeIs('admin.services.*') ? 'actif' : '' }}" href="{{ route('admin.services.index') }}"><span>🧰</span> Services</a>
  <a class="bo-lien {{ request()->routeIs('admin.communes.*') ? 'actif' : '' }}" href="{{ route('admin.communes.index') }}"><span>📍</span> Communes</a>
  <a class="bo-lien {{ request()->routeIs('admin.realisations.*') ? 'actif' : '' }}" href="{{ route('admin.realisations.index') }}"><span>📸</span> Réalisations</a>
  <a class="bo-lien {{ request()->routeIs('admin.galeries.*') ? 'actif' : '' }}" href="{{ route('admin.galeries.index') }}"><span>🖼</span> Galeries</a>
  <a class="bo-lien {{ request()->routeIs('admin.temoignages.*') ? 'actif' : '' }}" href="{{ route('admin.temoignages.index') }}"><span>★</span> Témoignages</a>
  <a class="bo-lien {{ request()->routeIs('admin.articles.*') ? 'actif' : '' }}" href="{{ route('admin.articles.index') }}"><span>✎</span> Blog</a>

  <div class="bo-nav-titre">Configuration</div>
  <a class="bo-lien {{ request()->routeIs('admin.reglages') ? 'actif' : '' }}" href="{{ route('admin.reglages') }}"><span>⚙</span> Réglages</a>
  @if(auth()->user()?->estSuperAdmin())
    <a class="bo-lien {{ request()->routeIs('admin.utilisateurs.*') ? 'actif' : '' }}" href="{{ route('admin.utilisateurs.index') }}"><span>👤</span> Utilisateurs</a>
  @endif
  <a class="bo-lien" href="{{ route('accueil') }}" target="_blank" rel="noopener"><span>↗</span> Voir le site</a>

  <form method="POST" action="{{ route('admin.logout') }}" style="margin-top:auto;padding-top:16px">
    @csrf
    <button type="submit" class="bo-btn" style="width:100%">Déconnexion</button>
  </form>
</aside>

<main class="bo-main">
  <div class="bo-topbar">
    <h1 class="bo-titre">@yield('titre', 'Administration')</h1>
    <div style="display:flex;align-items:center;gap:10px">
      @yield('actions')
      <button type="button" id="bo-theme-toggle" class="bo-theme-toggle" aria-label="Changer de thème">
        <span class="icone-sombre">☀ Mode clair</span>
        <span class="icone-clair">🌙 Mode sombre</span>
      </button>
    </div>
  </div>

  @if(session('succes'))
    <div class="bo-panneau succes">{{ session('succes') }}</div>
  @endif

  @if($errors->any())
    <div class="bo-panneau erreur">
      <ul>@foreach($errors->all() as $erreur)<li>{{ $erreur }}</li>@endforeach</ul>
    </div>
  @endif

  @yield('contenu')
</main>

<script>
document.getElementById('bo-theme-toggle')?.addEventListener('click', function () {
  var racine = document.documentElement;
  var clair = racine.getAttribute('data-theme') === 'light';
  if (clair) {
    racine.removeAttribute('data-theme');
  } else {
    racine.setAttribute('data-theme', 'light');
  }
  try { localStorage.setItem('vg_admin_theme', clair ? 'dark' : 'light'); } catch (e) {}
});
</script>

</body>
</html>
