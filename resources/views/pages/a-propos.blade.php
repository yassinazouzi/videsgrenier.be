@extends('layouts.site')

@section('titre', 'À propos — Videsgrenier.be, débarras à Bruxelles')
@section('description', 'Qui sommes-nous : une équipe bruxelloise de débarras qui vide, rachète et nettoie, avec plus de 500 chantiers réalisés.')

@section('contenu')
<section class="section">
  <div class="tete">
    <span class="eyebrow">À propos</span>
    <h2>Une équipe bruxelloise, pas un intermédiaire</h2>
  </div>

  <div class="faq" style="margin:0 auto">
    <p>
      Videsgrenier.be débarrasse maisons, appartements, caves et greniers dans les
      19 communes de Bruxelles. Nous ne sous-traitons pas : l’équipe qui vient chez vous
      est celle qui a établi le devis.
    </p>
    <p style="margin-top:16px">
      Notre différence tient en une ligne : <strong>ce que nous rachetons vient en déduction
      de votre facture</strong>. Meubles anciens, électroménager en état, vaisselle, livres,
      vinyles — nous estimons sur place et le montant part du prix du débarras.
    </p>
    <p style="margin-top:16px">
      Ce qui n’est pas racheté est trié : don aux associations bruxelloises, revente en
      brocante, recyclage en déchetterie agréée. Environ 80 % du volume évacué est revalorisé
      plutôt que jeté.
    </p>
  </div>

  <div class="grille-atouts" style="margin-top:44px">
    <div class="atout"><div class="n">500+</div><div class="l">Débarras réalisés</div></div>
    <div class="atout"><div class="n">2019</div><div class="l">Depuis</div></div>
    <div class="atout"><div class="n">19</div><div class="l">Communes</div></div>
    <div class="atout"><div class="n">80%</div><div class="l">Volume revalorisé</div></div>
  </div>
</section>

<section class="section">
  <div class="cta">
    <h2>Un projet de débarras&nbsp;?</h2>
    <p>Devis gratuit sous 24h, sans engagement.</p>
    <a href="{{ route('devis.form') }}" class="btn">Demander mon devis</a>
  </div>
</section>
@endsection
