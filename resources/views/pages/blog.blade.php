@extends('layouts.site')

@section('titre', 'Blog — conseils débarras et vide-maison à Bruxelles')
@section('description', 'Conseils pratiques pour préparer un débarras, vider une succession ou estimer le volume de votre logement à Bruxelles.')

@section('contenu')
<section class="section">
  <div class="tete">
    <span class="eyebrow">Blog</span>
    <h2>Conseils & guides pratiques</h2>
    <p>Tout ce qu’il faut savoir avant de vider un logement à Bruxelles.</p>
  </div>

  @if($articles->isEmpty())
    <p class="muet" style="text-align:center">Les premiers articles arrivent bientôt.</p>
  @else
    <div class="grille-services">
      @foreach($articles as $article)
        <a class="service" href="{{ route('blog.show', $article) }}">
          @if($article->categorie)<span class="eyebrow">{{ $article->categorie }}</span>@endif
          <h3 style="margin-top:8px">{{ $article->titre }}</h3>
          <p>{{ $article->extrait }}</p>
          <span class="mono muet" style="font-size:12.5px">{{ $article->publie_le?->translatedFormat('d F Y') }}</span>
        </a>
      @endforeach
    </div>

    <div style="margin-top:32px">{{ $articles->links() }}</div>
  @endif
</section>
@endsection
