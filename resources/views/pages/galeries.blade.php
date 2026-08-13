@extends('layouts.site')

@section('titre', 'Galeries photo — nos débarras à Bruxelles')
@section('description', 'Photos de nos chantiers de débarras, vide-maison et vide-appartement dans les communes de Bruxelles.')

@section('contenu')
<section class="section">
  <div class="tete">
    <span class="eyebrow">Galeries</span>
    <h2>Nos chantiers en images</h2>
    <p>Ce que donne un logement vidé, trié et nettoyé.</p>
  </div>

  @if($galeries->isEmpty())
    <p class="muet" style="text-align:center">Les premières galeries arrivent bientôt.</p>
  @else
    <div class="grille-real">
      @foreach($galeries as $galerie)
        <a class="real" href="{{ route('galeries.show', $galerie) }}">
          <div style="height:200px;background:#E9E5DC{{ $galerie->couverture ? " url('".asset($galerie->couverture)."') center/cover no-repeat" : '' }}"></div>
          <div class="leg">
            <strong>{{ $galerie->titre }}</strong><br>
            <span>{{ $galerie->photos_count }} photo{{ $galerie->photos_count > 1 ? 's' : '' }}</span>
          </div>
        </a>
      @endforeach
    </div>
  @endif
</section>
@endsection
