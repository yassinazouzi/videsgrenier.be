@extends('layouts.site')

@section('titre', 'Réalisations — nos débarras avant/après à Bruxelles')
@section('description', 'Photos avant/après de nos vide-maisons, débarras d’appartements, caves et greniers dans les communes de Bruxelles.')

@section('contenu')
<section class="section">
  <div class="tete">
    <span class="eyebrow">Portfolio</span>
    <h2>Avant / après</h2>
    <p>Chaque chantier est différent. Voici ce que ça donne.</p>
  </div>

  @if($realisations->isEmpty())
    <p class="muet" style="text-align:center">Nos premières réalisations arrivent bientôt.</p>
  @else
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

    <div style="margin-top:32px">{{ $realisations->links() }}</div>
  @endif
</section>
@endsection
