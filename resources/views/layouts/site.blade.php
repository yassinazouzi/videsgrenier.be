<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('titre', $reglages['site_titre'] ?? 'Videsgrenier.be')</title>
<meta name="description" content="@yield('description', 'Débarras, vide-maison et vide-appartement à Bruxelles. Devis gratuit sous 24h, rachat des objets de valeur déduit du prix.')">
@hasSection('noindex')<meta name="robots" content="noindex,nofollow">@endif
<link rel="canonical" href="{{ url()->current() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&family=IBM+Plex+Mono:wght@500;600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/style.css') }}">
@stack('tete')
</head>
<body>

<div class="bandeau">Devis gratuit sous 24h — <strong>rachat de vos objets déduit du prix</strong></div>

<header>
  <div class="header-inner">
    <a href="{{ route('accueil') }}" class="logo">Videsgrenier<span>.be</span></a>
    <nav class="nav-desktop">
      <a href="{{ route('accueil') }}">Accueil</a>
      <a href="{{ route('services.index') }}">Services</a>
      <a href="{{ route('realisations.index') }}">Réalisations</a>
      <a href="{{ route('galeries.index') }}">Galerie</a>
      <a href="{{ route('accueil') }}#zone">Communes</a>
      <a href="{{ route('blog.index') }}">Blog</a>
      <a href="{{ route('contact') }}">Contact</a>
    </nav>
    @if(!empty($reglages['telephone_public']))
      <a class="header-tel" href="tel:{{ preg_replace('/\s+/', '', $reglages['telephone_public']) }}">{{ $reglages['telephone_public'] }}</a>
    @endif
    <button class="burger" type="button" aria-expanded="false" aria-controls="nav-mobile" aria-label="Ouvrir le menu">☰</button>
  </div>
  <nav class="nav-mobile" id="nav-mobile">
    <a href="{{ route('accueil') }}">Accueil</a>
    <a href="{{ route('services.index') }}">Services</a>
    <a href="{{ route('realisations.index') }}">Réalisations</a>
    <a href="{{ route('galeries.index') }}">Galerie</a>
    <a href="{{ route('accueil') }}#zone">Communes</a>
    <a href="{{ route('blog.index') }}">Blog</a>
    <a href="{{ route('contact') }}">Contact</a>
    <a href="{{ route('devis.form') }}">Demander un devis</a>
  </nav>
</header>

@yield('contenu')

<footer>
  <div class="footer-inner">
    <div>
      <div class="logo">Videsgrenier<span>.be</span></div>
      <p>Débarras, vide-maison et vide-appartement dans les 19 communes de Bruxelles. On vide, on débarrasse, on rachète, on nettoie.</p>
      @if(!empty($reglages['facebook_url']) || !empty($reglages['instagram_url']) || !empty($reglages['tiktok_url']))
        <div class="footer-reseaux">
          @if(!empty($reglages['facebook_url']))
            <a href="{{ $reglages['facebook_url'] }}" target="_blank" rel="noopener" aria-label="Facebook">
              <svg viewBox="0 0 24 24" width="19" height="19" fill="currentColor" aria-hidden="true"><path d="M13.5 21v-7.6h2.55l.38-2.96h-2.93v-1.9c0-.86.24-1.44 1.47-1.44h1.57V4.8c-.27-.04-1.2-.12-2.28-.12-2.26 0-3.8 1.38-3.8 3.9v2.18H7.9v2.96h2.56V21h3.04Z"/></svg>
            </a>
          @endif
          @if(!empty($reglages['instagram_url']))
            <a href="{{ $reglages['instagram_url'] }}" target="_blank" rel="noopener" aria-label="Instagram">
              <svg viewBox="0 0 24 24" width="19" height="19" fill="currentColor" aria-hidden="true"><path d="M12 2.2c2.7 0 3 0 4.1.06 1.1.05 1.8.22 2.2.37.55.22.95.47 1.36.88.41.41.66.81.88 1.36.15.4.32 1.1.37 2.2.05 1.1.06 1.4.06 4.1s0 3-.06 4.1c-.05 1.1-.22 1.8-.37 2.2-.22.55-.47.95-.88 1.36-.41.41-.81.66-1.36.88-.4.15-1.1.32-2.2.37-1.1.05-1.4.06-4.1.06s-3 0-4.1-.06c-1.1-.05-1.8-.22-2.2-.37a3.7 3.7 0 0 1-1.36-.88 3.7 3.7 0 0 1-.88-1.36c-.15-.4-.32-1.1-.37-2.2C2.2 15 2.2 14.7 2.2 12s0-3 .06-4.1c.05-1.1.22-1.8.37-2.2.22-.55.47-.95.88-1.36.41-.41.81-.66 1.36-.88.4-.15 1.1-.32 2.2-.37C8.2 2.2 8.5 2.2 12 2.2Zm0 1.8c-2.66 0-2.97 0-4.02.06-.97.04-1.5.2-1.85.34-.46.18-.8.4-1.15.75s-.57.69-.75 1.15c-.14.35-.3.88-.34 1.85C3.83 9.03 3.83 9.34 3.83 12s0 2.97.06 4.02c.04.97.2 1.5.34 1.85.18.46.4.8.75 1.15s.69.57 1.15.75c.35.14.88.3 1.85.34C8.03 20.17 8.34 20.17 11 20.17h1c2.66 0 2.97 0 4.02-.06.97-.04 1.5-.2 1.85-.34.46-.18.8-.4 1.15-.75s.57-.69.75-1.15c.14-.35.3-.88.34-1.85.05-1.05.06-1.36.06-4.02s0-2.97-.06-4.02c-.04-.97-.2-1.5-.34-1.85a3.1 3.1 0 0 0-.75-1.15 3.1 3.1 0 0 0-1.15-.75c-.35-.14-.88-.3-1.85-.34C14.97 4 14.66 4 12 4Zm0 3.4a4.6 4.6 0 1 1 0 9.2 4.6 4.6 0 0 1 0-9.2Zm0 1.8a2.8 2.8 0 1 0 0 5.6 2.8 2.8 0 0 0 0-5.6Zm4.8-2a1.08 1.08 0 1 1 0 2.16 1.08 1.08 0 0 1 0-2.16Z"/></svg>
            </a>
          @endif
          @if(!empty($reglages['tiktok_url']))
            <a href="{{ $reglages['tiktok_url'] }}" target="_blank" rel="noopener" aria-label="TikTok">
              <svg viewBox="0 0 24 24" width="19" height="19" fill="currentColor" aria-hidden="true"><path d="M16.6 2h-3.2v13.3a2.9 2.9 0 1 1-2.05-2.77v-3.3a6.2 6.2 0 1 0 5.25 6.13V8.6c1.15.85 2.56 1.35 4.1 1.35V6.75c-2.1 0-3.9-1.5-4.1-4.75Z"/></svg>
            </a>
          @endif
        </div>
      @endif
    </div>
    <div>
      <h4>Services</h4>
      <ul>
        @foreach($servicesFooter as $s)
          <li><a href="{{ route('services.show', $s) }}">{{ $s->titre }}</a></li>
        @endforeach
      </ul>
    </div>
    <div>
      <h4>Communes</h4>
      <ul>
        @foreach($communesFooter as $c)
          <li><a href="{{ route('commune.show', $c) }}">Débarras {{ $c->nom }}</a></li>
        @endforeach
      </ul>
    </div>
    <div>
      <h4>Contact</h4>
      <ul>
        @if(!empty($reglages['telephone_public']))
          <li><a href="tel:{{ preg_replace('/\s+/', '', $reglages['telephone_public']) }}">{{ $reglages['telephone_public'] }}</a></li>
        @endif
        @if(!empty($reglages['email_devis']))
          <li><a href="mailto:{{ $reglages['email_devis'] }}">{{ $reglages['email_devis'] }}</a></li>
        @endif
        <li><a href="{{ route('devis.form') }}">Demander un devis</a></li>
        <li><a href="{{ route('a-propos') }}">À propos</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bas">
    <span>© {{ date('Y') }} Videsgrenier.be</span>
    <span>
      <a href="{{ route('mentions-legales') }}">Mentions légales</a> ·
      <a href="{{ route('confidentialite') }}">Confidentialité</a>
    </span>
  </div>
</footer>

@include('partials.whatsapp')
@include('partials.cookies')

<script>
document.querySelector('.burger')?.addEventListener('click', function () {
  const nav = document.getElementById('nav-mobile');
  const ouvert = nav.classList.toggle('ouvert');
  this.setAttribute('aria-expanded', ouvert ? 'true' : 'false');
});
document.querySelector('.wa-infobulle .fermer')?.addEventListener('click', function () {
  this.closest('.wa-infobulle').remove();
});

// Formulaire de devis replié derrière un bouton sur mobile : il occupait
// tout le premier écran et repoussait l'argumentaire hors de vue.
(function () {
  var SEUIL_MOBILE = 720;
  var carte = document.querySelector('.devis-repliable');
  if (!carte) return;

  var bascule = carte.querySelector('.devis-bascule');
  var corps = carte.querySelector('.devis-corps');
  if (!bascule || !corps) return;

  function estMobile() { return window.innerWidth <= SEUIL_MOBILE; }

  function ouvrir(defilerVers) {
    carte.classList.add('ouvert');
    corps.hidden = false;
    bascule.setAttribute('aria-expanded', 'true');
    bascule.textContent = 'Masquer le formulaire';
    if (defilerVers) {
      corps.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
  }

  function replier() {
    carte.classList.remove('ouvert');
    corps.hidden = true;
    bascule.setAttribute('aria-expanded', 'false');
    bascule.textContent = 'Demander mon devis gratuit';
  }

  function appliquer() {
    if (estMobile()) {
      bascule.hidden = false;
      // Un formulaire déjà rempli (erreur de validation renvoyée par le
      // serveur) doit rester ouvert, sinon le visiteur perd ses saisies.
      if (carte.querySelector('.champ [role="alert"], [role="alert"]')) {
        ouvrir(false);
      } else if (!carte.classList.contains('ouvert')) {
        replier();
      }
    } else {
      bascule.hidden = true;
      corps.hidden = false;
    }
  }

  bascule.addEventListener('click', function () {
    carte.classList.contains('ouvert') ? replier() : ouvrir(true);
  });

  // Les liens « Demander un devis » pointent vers #devis : sur mobile,
  // ils doivent déplier le formulaire plutôt que sauter sur un bloc replié.
  document.querySelectorAll('a[href$="#devis"]').forEach(function (lien) {
    lien.addEventListener('click', function () {
      if (estMobile()) ouvrir(true);
    });
  });

  if (window.location.hash === '#devis') ouvrir(false);

  appliquer();
  window.addEventListener('resize', appliquer);
})();
</script>
@stack('scripts')
</body>
</html>
