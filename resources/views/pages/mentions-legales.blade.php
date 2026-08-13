@extends('layouts.site')

@section('titre', 'Mentions légales — Videsgrenier.be')
@section('noindex', true)

@section('contenu')
<section class="section">
  <div class="tete"><h2>Mentions légales</h2></div>

  <div class="faq" style="margin:0 auto">
    <h3>Éditeur du site</h3>
    <p class="muet">
      {{ config('site.entreprise.nom') }}<br>
      {{ config('site.entreprise.rue') }}, {{ config('site.entreprise.code_postal') }} {{ config('site.entreprise.ville') }}, Belgique<br>
      @if(!empty($reglages['telephone_public'])){{ $reglages['telephone_public'] }}<br>@endif
      @if(!empty($reglages['email_devis'])){{ $reglages['email_devis'] }}@endif
    </p>
    <p class="muet" style="margin-top:12px">
      <strong>À compléter :</strong> numéro d’entreprise (BCE/TVA), forme juridique,
      nom du responsable de la publication.
    </p>

    <h3 style="margin-top:26px">Hébergement</h3>
    <p class="muet">
      OVH SAS — 2 rue Kellermann, 59100 Roubaix, France.
    </p>

    <h3 style="margin-top:26px">Propriété intellectuelle</h3>
    <p class="muet">
      L’ensemble des contenus (textes, photographies, identité visuelle) est la propriété de
      {{ config('site.entreprise.nom') }}, sauf mention contraire. Toute reproduction sans
      autorisation est interdite.
    </p>
  </div>
</section>
@endsection
