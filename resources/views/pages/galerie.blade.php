@extends('layouts.site')

@section('titre', $galerie->titre.' — galerie photo | Videsgrenier.be')
@section('description', Str::limit($galerie->description ?: 'Photos du chantier '.$galerie->titre.' à Bruxelles.', 155))

@section('contenu')
<section class="section">
  <div class="tete">
    <span class="eyebrow">Galerie</span>
    <h1 style="font-size:clamp(30px,4.5vw,46px)">{{ $galerie->titre }}</h1>
    @if($galerie->description)<p>{{ $galerie->description }}</p>@endif
  </div>

  @if($galerie->photos->isEmpty())
    <p class="muet" style="text-align:center">Aucune photo dans cette galerie.</p>
  @else
    <div class="grille-real">
      @foreach($galerie->photos as $photo)
        <figure class="real">
          <img src="{{ asset($photo->url) }}" alt="{{ $photo->alt }}" loading="lazy"
               style="width:100%;height:220px;object-fit:cover">
        </figure>
      @endforeach
    </div>
  @endif
</section>

@if($autres->isNotEmpty())
<section class="section">
  <div class="tete"><h2>Autres galeries</h2></div>
  <div class="grille-real">
    @foreach($autres as $autre)
      <a class="real" href="{{ route('galeries.show', $autre) }}">
        <div style="height:160px;background:#E9E5DC{{ $autre->couverture ? " url('".asset($autre->couverture)."') center/cover no-repeat" : '' }}"></div>
        <div class="leg"><strong>{{ $autre->titre }}</strong></div>
      </a>
    @endforeach
  </div>
</section>
@endif

<section class="section">
  <div class="cta">
    <h2>Un logement à vider&nbsp;?</h2>
    <p>Devis gratuit sous 24h, rachat de vos objets déduit du prix.</p>
    <a href="{{ route('devis.form') }}" class="btn">Demander mon devis</a>
  </div>
</section>
@endsection
