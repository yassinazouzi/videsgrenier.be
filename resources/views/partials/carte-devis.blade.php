@php
  // Carte de devis du hero, repliable sur mobile.
  $titre ??= 'Votre devis en 2 min';
  $sousTitre ??= 'Réponse sous 24h, sans engagement.';
  $communePreselect ??= null;
  $prestationPreselect ??= null;
@endphp

<div class="devis-carte devis-repliable" id="devis">
  <h2>{{ $titre }}</h2>
  <p>{{ $sousTitre }}</p>

  {{-- Masqué par défaut : c'est le JavaScript qui l'affiche sur petit écran.
       Sans JavaScript, le formulaire reste donc entièrement visible. --}}
  <button type="button" class="btn btn-jaune btn-bloc devis-bascule"
          aria-expanded="false" aria-controls="devis-corps" hidden>
    Demander mon devis gratuit
  </button>

  <div class="devis-corps" id="devis-corps">
    @include('partials.form-devis', [
        'communePreselect' => $communePreselect,
        'prestationPreselect' => $prestationPreselect,
    ])
  </div>
</div>
