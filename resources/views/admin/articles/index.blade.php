@extends('admin.layout')
@section('titre', 'Blog')

@section('actions')
  <a href="{{ route('admin.articles.create') }}" class="bo-btn bo-btn-cobalt">Nouvel article</a>
@endsection

@section('contenu')
<div class="bo-panneau">
  <table class="bo-table">
    <thead><tr><th>Article</th><th>Catégorie</th><th>Publication</th><th>État</th><th></th></tr></thead>
    <tbody>
      @forelse($articles as $article)
        <tr>
          <td>
            <div class="titre-cell">{{ $article->titre }}</div>
            <div class="sous">/blog/{{ $article->slug }}</div>
          </td>
          <td class="sous">{{ $article->categorie ?: '—' }}</td>
          <td class="sous">{{ $article->publie_le?->format('d/m/Y H:i') ?: '—' }}</td>
          <td>
            @if($article->statut === 'publie' && $article->publie_le?->isFuture())
              <span class="badge badge-contacte">Planifié</span>
            @else
              <span class="badge {{ $article->statut === 'publie' ? 'badge-publie' : 'badge-perdu' }}">
                {{ $article->statut === 'publie' ? 'Publié' : 'Brouillon' }}
              </span>
            @endif
          </td>
          <td style="display:flex;gap:8px">
            <a href="{{ route('admin.articles.edit', $article) }}" class="bo-btn bo-btn-sm">Éditer</a>
            <form method="POST" action="{{ route('admin.articles.destroy', $article) }}"
                  onsubmit="return confirm('Supprimer cet article ?')">
              @csrf @method('DELETE')
              <button class="bo-btn bo-btn-sm bo-btn-danger">Suppr.</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="muet">Aucun article.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div style="margin-top:18px">{{ $articles->links() }}</div>
</div>
@endsection
