@extends('layouts.site')

@section('titre', $realisation->titre.' — débarras à '.$realisation->commune)
@section('description', Str::limit($realisation->description, 155))

@section('contenu')
<section class="section">
  <div class="tete">
    <span class="eyebrow">{{ $realisation->commune }} · {{ $realisation->type_presta }}</span>
    <h2>{{ $realisation->titre }}</h2>
    <p>{{ $realisation->description }}</p>
  </div>

  <div class="real" style="max-width:820px;margin:0 auto">
    <div class="paire" style="height:320px">
      <div class="av" @if($realisation->photo_avant) style="background-image:url('{{ asset($realisation->photo_avant) }}')" @endif
           role="img" aria-label="{{ $realisation->type_presta }} {{ $realisation->commune }} avant"></div>
      <div class="ap" @if($realisation->photo_apres) style="background-image:url('{{ asset($realisation->photo_apres) }}')" @endif
           role="img" aria-label="{{ $realisation->type_presta }} {{ $realisation->commune }} après"></div>
    </div>
    <div class="leg">
      <strong>Durée du chantier</strong> <span>{{ $realisation->duree }}</span>
    </div>
  </div>
</section>

@if($autres->isNotEmpty())
<section class="section">
  <div class="tete"><h2>Autres réalisations</h2></div>
  <div class="grille-real">
    @foreach($autres as $autre)
      <a class="real" href="{{ route('realisations.show', $autre) }}">
        <div class="paire">
          <div class="av" @if($autre->photo_avant) style="background-image:url('{{ asset($autre->photo_avant) }}')" @endif></div>
          <div class="ap" @if($autre->photo_apres) style="background-image:url('{{ asset($autre->photo_apres) }}')" @endif></div>
        </div>
        <div class="leg"><strong>{{ $autre->titre }}</strong><br><span>{{ $autre->type_presta }}</span></div>
      </a>
    @endforeach
  </div>
</section>
@endif

<section class="section">
  <div class="cta">
    <h2>Un chantier similaire&nbsp;?</h2>
    <p>Devis gratuit sous 24h, rachat déduit du prix.</p>
    <a href="{{ route('devis.form') }}" class="btn">Demander mon devis</a>
  </div>
</section>
@endsection
