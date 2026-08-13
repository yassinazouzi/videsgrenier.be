<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow,noarchive">
<title>Connexion — Videsgrenier.be</title>
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
<body style="background:var(--bo-fond);color:var(--bo-texte);min-height:100vh;display:grid;place-items:center;padding:20px">

<button type="button" id="bo-theme-toggle" class="bo-theme-toggle" aria-label="Changer de thème"
        style="position:fixed;top:20px;right:20px">
  <span class="icone-sombre">☀ Mode clair</span>
  <span class="icone-clair">🌙 Mode sombre</span>
</button>

<div class="bo-panneau" style="width:100%;max-width:380px;margin:0">
  <div class="bo-logo" style="padding-left:0">Videsgrenier<span>.be</span></div>
  <p class="muet" style="margin-bottom:20px;font-size:14px">Accès réservé à l’administration.</p>

  @if($errors->any())
    <div class="bo-texte-erreur" style="font-size:14px;margin-bottom:14px">
      @foreach($errors->all() as $erreur)<div>{{ $erreur }}</div>@endforeach
    </div>
  @endif

  <form method="POST" action="{{ route('admin.login.post') }}">
    @csrf
    <div class="bo-champ">
      <label for="email">E-mail</label>
      <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
    </div>
    <div class="bo-champ">
      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="password" required autocomplete="current-password">
    </div>
    <label style="display:flex;align-items:center;gap:8px;font-size:14px;margin-bottom:16px">
      <input type="checkbox" name="memoire" value="1"> Rester connecté
    </label>
    <button type="submit" class="bo-btn bo-btn-cobalt" style="width:100%;justify-content:center">Se connecter</button>
  </form>
</div>

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
