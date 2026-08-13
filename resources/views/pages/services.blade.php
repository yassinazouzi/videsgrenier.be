@extends('layouts.site')

@section('titre', 'Nos services de débarras à Bruxelles — vide-maison, cave, succession')
@section('description', 'Vide-maison, débarras d’appartement, cave et grenier, succession, rachat de meubles et nettoyage après débarras à Bruxelles.')

@section('contenu')
<section class="section">
  <div class="tete">
    <span class="eyebrow">Nos prestations</span>
    <h1 style="font-size:clamp(30px,4.5vw,46px)">Un seul interlocuteur pour tout vider</h1>
    <p>Du premier carton au coup de balai final, dans les 19 communes de Bruxelles.</p>
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

<section class="section">
  <div class="cta">
    <h2>Besoin d’un chiffrage&nbsp;?</h2>
    <p>Devis gratuit sous 24h, rachat de vos objets déduit du prix.</p>
    <a href="{{ route('devis.form') }}" class="btn">Demander mon devis</a>
  </div>
</section>
@endsection
