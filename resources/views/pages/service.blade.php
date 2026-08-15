@extends('layouts.site')

@section('titre', $service->meta_title ?: $service->titre.' à Bruxelles — devis gratuit 24h')
@section('description', $service->meta_description ?: $service->extrait)

@push('tete')
{{-- JSON-LD construit en PHP : hors bloc PHP, Blade prendrait la clé "at-context" pour une directive. --}}
@php
  $jsonldService = json_encode([
      '@context' => 'https://schema.org',
      '@type' => 'Service',
      'name' => $service->titre,
      'description' => $service->extrait,
      'provider' => [
          '@type' => 'LocalBusiness',
          'name' => config('site.entreprise.nom'),
          'telephone' => $reglages['telephone_public'] ?? null,
          'url' => url('/'),
      ],
      'areaServed' => $communes->map(fn ($c) => ['@type' => 'City', 'name' => $c->nom])->all(),
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
    <span class="eyebrow">Service · Bruxelles</span>
    <h1>{{ $service->titre }}</h1>
    <p class="hero-sub">{{ $service->extrait }}</p>
    <div class="hero-cta">
      <a href="#devis" class="btn btn-cobalt">Demander un devis</a>
      @if(!empty($reglages['telephone_public']))
        <a href="tel:{{ preg_replace('/\s+/', '', $reglages['telephone_public']) }}" class="btn">{{ $reglages['telephone_public'] }}</a>
      @endif
    </div>
  </div>

  @include('partials.carte-devis', [
      'titre' => 'Devis gratuit',
      'sousTitre' => 'Réponse sous 24h.',
      'prestationPreselect' => $service->titre,
      'communes' => $communes,
  ])
</section>

@if($service->contenu)
<section class="section">
  <div class="faq" style="margin:0 auto">
    {!! nl2br(e($service->contenu)) !!}
  </div>
</section>
@endif

<section class="section">
  <div class="zone">
    <div>
      <span class="eyebrow">Zone d’intervention</span>
      <h2>{{ $service->titre }} dans votre commune</h2>
      <p>Nous couvrons les 19 communes de Bruxelles-Capitale.</p>
    </div>
    <div class="communes">
      @foreach($communes as $commune)
        <a class="commune" href="{{ route('commune.show', $commune) }}">{{ $commune->nom }}</a>
      @endforeach
    </div>
  </div>
</section>
@endsection
