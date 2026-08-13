@extends('layouts.site')

@section('titre', $article->meta_title ?: $article->titre)
@section('description', $article->meta_description ?: $article->extrait)

@push('tete')
{{-- JSON-LD construit en PHP : hors bloc PHP, Blade prendrait la clé "at-context" pour une directive. --}}
@php
  // array_filter retire les clés absentes (ex. image) plutôt que de publier
  // "image":null — invalide pour le rich result Article de Google.
  $jsonldArticle = json_encode(array_filter([
      '@context' => 'https://schema.org',
      '@type' => 'BlogPosting',
      'headline' => $article->titre,
      'description' => $article->extrait,
      'datePublished' => $article->publie_le?->toAtomString(),
      'image' => $article->image_une ? asset($article->image_une) : null,
      'author' => ['@type' => 'Organization', 'name' => config('site.entreprise.nom')],
      'publisher' => ['@type' => 'Organization', 'name' => config('site.entreprise.nom')],
      'mainEntityOfPage' => url()->current(),
  ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp
<script type="application/ld+json">
{!! $jsonldArticle !!}
</script>
@endpush

@section('contenu')
<section class="section">
  <div class="tete">
    @if($article->categorie)<span class="eyebrow">{{ $article->categorie }}</span>@endif
    <h1 style="font-size:clamp(30px,4.5vw,46px);margin-top:10px">{{ $article->titre }}</h1>
    <p class="mono muet" style="font-size:13px">{{ $article->publie_le?->translatedFormat('d F Y') }}</p>
  </div>

  @if($article->image_une)
    <img src="{{ asset($article->image_une) }}" alt="{{ $article->titre }}"
         style="max-width:820px;margin:0 auto 32px;border:1.5px solid var(--encre);border-radius:var(--r)">
  @endif

  {{-- Contenu rédigé exclusivement par les comptes admin authentifiés (§9 sécurité) :
       rendu en HTML brut pour permettre un vrai balisage H2/H3/listes, essentiel au SEO. --}}
  <div class="article-corps">
    {!! $article->contenu !!}
  </div>
</section>

@if($autres->isNotEmpty())
<section class="section">
  <div class="tete"><h2>À lire aussi</h2></div>
  <div class="grille-services">
    @foreach($autres as $autre)
      <a class="service" href="{{ route('blog.show', $autre) }}">
        <h3>{{ $autre->titre }}</h3>
        <p>{{ $autre->extrait }}</p>
      </a>
    @endforeach
  </div>
</section>
@endif

<section class="section">
  <div class="cta">
    <h2>Un débarras à prévoir&nbsp;?</h2>
    <p>Devis gratuit sous 24h, rachat de vos objets déduit du prix.</p>
    <a href="{{ route('devis.form') }}" class="btn">Demander mon devis</a>
  </div>
</section>
@endsection
