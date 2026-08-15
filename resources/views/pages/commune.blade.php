@extends('layouts.site')

@section('titre', $commune->meta_title ?: 'Débarras & vide-maison à '.$commune->nom.' — devis gratuit 24h')
@section('description', $commune->meta_description ?: 'Débarras, vide-maison et vide-appartement à '.$commune->nom.' ('.$commune->code_postal.'). Intervention sous 24–48h, rachat de vos objets déduit du prix.')

@push('tete')
{{-- JSON-LD construit en PHP : hors bloc PHP, Blade prendrait la clé "at-context" pour une directive. --}}
@php
  $jsonldService = json_encode([
      '@context' => 'https://schema.org',
      '@type' => 'Service',
      'name' => 'Débarras et vide-maison à '.$commune->nom,
      'serviceType' => 'Débarras, vide-maison, vide-appartement',
      'provider' => [
          '@type' => 'LocalBusiness',
          'name' => config('site.entreprise.nom'),
          'telephone' => $reglages['telephone_public'] ?? null,
          'url' => url('/'),
      ],
      'areaServed' => [
          '@type' => 'City',
          'name' => $commune->nom,
          'postalCode' => $commune->code_postal,
          'addressCountry' => 'BE',
      ],
      'url' => url()->current(),
  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp
<script type="application/ld+json">
{!! $jsonldService !!}
</script>
@endpush

@section('contenu')

<section class="hero">
  <div>
    <span class="eyebrow">{{ $commune->nom }} · {{ $commune->code_postal }}</span>
    <h1>Débarras & vide-maison à <em>{{ $commune->nom }}</em></h1>

    {{-- Réponse directe en haut de page : levier AEO --}}
    <p class="hero-sub">
      Nous débarrassons appartements, maisons, caves et greniers à {{ $commune->nom }}
      <strong>sous 24 à 48h</strong>, avec rachat de vos objets de valeur déduit du prix.
    </p>

    <div class="hero-points">
      <span class="hero-point"><span class="p">✓</span> Devis gratuit sous 24h</span>
      <span class="hero-point"><span class="p">✓</span> Logement rendu propre</span>
    </div>
    <div class="hero-cta">
      <a href="#devis" class="btn btn-cobalt">Devis pour {{ $commune->nom }}</a>
      @if(!empty($reglages['telephone_public']))
        <a href="tel:{{ preg_replace('/\s+/', '', $reglages['telephone_public']) }}" class="btn">{{ $reglages['telephone_public'] }}</a>
      @endif
    </div>
  </div>

  @include('partials.carte-devis', [
      'titre' => 'Devis à '.$commune->nom,
      'communePreselect' => $commune->nom,
  ])
</section>

@if($commune->intro)
<section class="section">
  <div class="faq" style="margin:0 auto">
    {!! nl2br(e($commune->intro)) !!}
  </div>
</section>
@endif

<section class="section">
  <div class="tete">
    <span class="eyebrow">Nos prestations à {{ $commune->nom }}</span>
    <h2>Ce que nous débarrassons</h2>
  </div>
  <div class="grille-services">
    @foreach($services as $service)
      <a class="service" href="{{ route('services.show', $service) }}">
        <div class="ic">{{ $service->icone }}</div>
        <h3>{{ $service->titre }}</h3>
        <p>{{ $service->extrait }}</p>
      </a>
    @endforeach
  </div>
</section>

@if($realisations->isNotEmpty())
<section class="section">
  <div class="tete">
    <span class="eyebrow">Chantiers récents</span>
    <h2>Nos débarras à {{ $commune->nom }}</h2>
  </div>
  <div class="grille-real">
    @foreach($realisations as $realisation)
      <a class="real" href="{{ route('realisations.show', $realisation) }}">
        <div class="paire">
          <div class="av" @if($realisation->photo_avant) style="background-image:url('{{ asset($realisation->photo_avant) }}')" @endif></div>
          <div class="ap" @if($realisation->photo_apres) style="background-image:url('{{ asset($realisation->photo_apres) }}')" @endif></div>
        </div>
        <div class="leg">
          <strong>{{ $realisation->titre }}</strong><br>
          <span>{{ $realisation->type_presta }} · {{ $realisation->duree }}</span>
        </div>
      </a>
    @endforeach
  </div>
</section>
@endif

@if($temoignages->isNotEmpty())
<section class="section">
  <div class="tete">
    <span class="eyebrow">Avis à {{ $commune->nom }}</span>
    <h2>Ce qu’en disent nos clients</h2>
  </div>
  <div class="grille-temo">
    @foreach($temoignages as $temoignage)
      <div class="temo">
        <div class="etoiles">{{ str_repeat('★', $temoignage->note) }}</div>
        <p>{{ $temoignage->texte }}</p>
        <div class="qui"><strong>{{ $temoignage->auteur }}</strong> · {{ $temoignage->commune }}</div>
      </div>
    @endforeach
  </div>
</section>
@endif

<section class="section">
  <div class="zone">
    <div>
      <span class="eyebrow">Autres communes</span>
      <h2>Nous intervenons aussi à proximité</h2>
      <p>Partout dans la Région de Bruxelles-Capitale.</p>
    </div>
    <div class="communes">
      @foreach($autresCommunes as $autre)
        <a class="commune" href="{{ route('commune.show', $autre) }}">{{ $autre->nom }}</a>
      @endforeach
    </div>
  </div>
</section>

@endsection
