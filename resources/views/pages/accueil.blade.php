@extends('layouts.site')

@section('titre', 'Débarras & vide-maison à Bruxelles — devis gratuit 24h | Videsgrenier.be')
@section('description', 'Vide-maison, débarras d’appartement, cave et grenier dans les 19 communes de Bruxelles. Rachat de vos objets déduit du prix. Devis gratuit sous 24h.')

@push('tete')
@include('partials.jsonld-localbusiness')
{{-- JSON-LD construit en PHP : hors bloc PHP, Blade prendrait la clé "at-context" pour une directive. --}}
@php
  $jsonldFaq = json_encode([
      '@context' => 'https://schema.org',
      '@type' => 'FAQPage',
      'mainEntity' => collect($faq)->map(fn ($f) => [
          '@type' => 'Question',
          'name' => $f['q'],
          'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['r']],
      ])->all(),
  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp
<script type="application/ld+json">
{!! $jsonldFaq !!}
</script>
@endpush

@section('contenu')

<section class="hero-section">
  {{-- Fond animé : vidéo si configurée (YouTube ou fichier), sinon slider de photos,
       sinon dégradé animé — jamais de média fictif. --}}
  <div class="hero-fond" aria-hidden="true">
    @if($heroVideo && $heroVideo['type'] === 'youtube')
      <div class="hero-fond-youtube">
        <iframe
          src="https://www.youtube-nocookie.com/embed/{{ $heroVideo['id'] }}?autoplay=1&mute=1&loop=1&playlist={{ $heroVideo['id'] }}&controls=0&showinfo=0&modestbranding=1&iv_load_policy=3&disablekb=1&rel=0&playsinline=1"
          title="" frameborder="0" allow="autoplay; encrypted-media" tabindex="-1"></iframe>
      </div>
    @elseif($heroVideo && $heroVideo['type'] === 'fichier')
      <video class="hero-fond-video" autoplay muted loop playsinline>
        <source src="{{ $heroVideo['url'] }}">
      </video>
    @elseif(count($heroPhotos) > 1)
      @php $pas = round(100 / count($heroPhotos), 4); @endphp
      <style>
        @keyframes hero-diapo{
          0%{opacity:0}
          {{ min($pas * .12, 6) }}%{opacity:1}
          {{ max($pas - min($pas * .12, 6), 0) }}%{opacity:1}
          {{ $pas }}%{opacity:0}
          100%{opacity:0}
        }
      </style>
      @foreach($heroPhotos as $i => $photo)
        <div class="hero-fond-image" style="background-image:url('{{ asset($photo) }}');animation-duration:{{ count($heroPhotos) * 6 }}s;animation-delay:{{ $i * 6 }}s"></div>
      @endforeach
    @elseif(count($heroPhotos) === 1)
      <div class="hero-fond-image" style="background-image:url('{{ asset($heroPhotos[0]) }}');opacity:1;animation:none"></div>
    @endif
  </div>

  <section class="hero">
    <div>
      <span class="eyebrow">Bruxelles · 19 communes</span>
      <h1>On vide, on débarrasse, <em>on rachète</em>, on nettoie.</h1>
      <p class="hero-sub">
        Vide-maison, débarras d’appartement, cave et grenier à Bruxelles.
        Les objets de valeur que nous rachetons sont <strong>déduits de votre devis</strong>.
      </p>
      <div class="hero-points">
        <span class="hero-point"><span class="p">✓</span> Devis gratuit sous 24h</span>
        <span class="hero-point"><span class="p">✓</span> Intervention 24–48h</span>
        <span class="hero-point"><span class="p">✓</span> Logement rendu propre</span>
      </div>
      <div class="hero-cta">
        <a href="#devis" class="btn btn-cobalt">Demander un devis</a>
        @if(!empty($reglages['telephone_public']))
          <a href="tel:{{ preg_replace('/\s+/', '', $reglages['telephone_public']) }}" class="btn">
            {{ $reglages['telephone_public'] }}
          </a>
        @endif
      </div>
    </div>

    <div class="devis-carte" id="devis">
      <h2>Votre devis en 2 min</h2>
      <p>Réponse sous 24h, sans engagement.</p>
      @include('partials.form-devis')
    </div>
  </section>
</section>

<section class="section">
  <div class="tete">
    <span class="eyebrow">Nos prestations</span>
    <h2>Ce que nous débarrassons</h2>
    <p>Un seul interlocuteur, du premier carton au coup de balai final.</p>
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

<section class="etapes">
  <div class="etapes-inner">
    <div class="tete">
      <span class="eyebrow">Comment ça marche</span>
      <h2>Trois étapes, zéro surprise</h2>
      <p>Le prix annoncé est le prix final, rachat déduit.</p>
    </div>
    <div class="grille-etapes">
      <div class="etape">
        <h3>Vous décrivez</h3>
        <p>Formulaire ou WhatsApp, avec quelques photos si possible. On vous répond sous 24h.</p>
      </div>
      <div class="etape">
        <h3>On chiffre sur place</h3>
        <p>Visite gratuite, estimation du volume et du rachat. Devis écrit, ferme et définitif.</p>
      </div>
      <div class="etape">
        <h3>On débarrasse</h3>
        <p>Évacuation, tri, recyclage en déchetterie agréée, puis nettoyage du logement.</p>
      </div>
    </div>
  </div>
</section>

@if($realisations->isNotEmpty())
<section class="section">
  <div class="tete">
    <span class="eyebrow">Réalisations</span>
    <h2>Avant / après</h2>
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

<section class="section" id="zone">
  <div class="zone">
    <div>
      <span class="eyebrow">Zone d’intervention</span>
      <h2>Les 19 communes de Bruxelles</h2>
      <p>Nous intervenons partout dans la Région de Bruxelles-Capitale, souvent sous 24 à 48h.</p>
    </div>
    <div class="communes">
      @foreach($communes as $commune)
        <a class="commune" href="{{ route('commune.show', $commune) }}">{{ $commune->nom }}</a>
      @endforeach
    </div>
  </div>
</section>

<section class="section">
  <div class="grille-atouts">
    <div class="atout"><div class="n">500+</div><div class="l">Débarras réalisés</div><div class="d">à Bruxelles depuis 2019</div></div>
    <div class="atout"><div class="n">24h</div><div class="l">Devis envoyé</div><div class="d">jours ouvrables</div></div>
    <div class="atout"><div class="n">19</div><div class="l">Communes couvertes</div><div class="d">Bruxelles-Capitale</div></div>
    <div class="atout"><div class="n">80%</div><div class="l">Objets revalorisés</div><div class="d">rachat, don, recyclage</div></div>
  </div>
</section>

@if($temoignages->isNotEmpty())
<section class="section">
  <div class="tete">
    <span class="eyebrow">Avis clients</span>
    <h2>Ils nous ont fait confiance</h2>
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
  <div class="tete">
    <span class="eyebrow">Questions fréquentes</span>
    <h2>Vous vous demandez…</h2>
  </div>
  <div class="faq" style="margin:0 auto">
    @foreach($faq as $item)
      <details class="q">
        <summary>{{ $item['q'] }}</summary>
        <div class="rep">{{ $item['r'] }}</div>
      </details>
    @endforeach
  </div>
</section>

<section class="section">
  <div class="cta">
    <h2>Un débarras à prévoir&nbsp;?</h2>
    <p>Décrivez-nous votre logement, nous revenons vers vous sous 24h avec un prix ferme.</p>
    <a href="#devis" class="btn">Demander mon devis gratuit</a>
  </div>
</section>

@endsection
