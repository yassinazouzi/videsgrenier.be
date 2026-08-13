@extends('admin.layout')
@section('titre', $article->exists ? 'Éditer : '.$article->titre : 'Nouvel article')

@section('actions')
  <a href="{{ route('admin.articles.index') }}" class="bo-btn">Retour</a>
@endsection

@section('contenu')
<form method="POST" enctype="multipart/form-data"
      action="{{ $article->exists ? route('admin.articles.update', $article) : route('admin.articles.store') }}">
  @csrf
  @if($article->exists) @method('PUT') @endif

  <div class="bo-cols">
    <div>
      <div class="bo-panneau">
        <div class="bo-panneau-tete"><h3>Rédaction</h3></div>

        <div class="bo-champ">
          <label for="titre">Titre *</label>
          <input type="text" id="titre" name="titre" required value="{{ old('titre', $article->titre) }}">
        </div>

        <div class="bo-champ">
          <label for="extrait">Chapô / extrait</label>
          <textarea id="extrait" name="extrait" rows="2" maxlength="320">{{ old('extrait', $article->extrait) }}</textarea>
        </div>

        <div class="bo-champ">
          <label for="contenu">Contenu (HTML : &lt;h2&gt;, &lt;h3&gt;, &lt;p&gt;, &lt;ul&gt;&lt;li&gt;, &lt;strong&gt;)</label>
          <textarea id="contenu" name="contenu" rows="20">{{ old('contenu', $article->contenu) }}</textarea>
          <span class="sous">Affiché tel quel sur la page publique : structurez avec de vrais titres H2/H3 pour le SEO.</span>
        </div>
      </div>
    </div>

    <div>
      <div class="bo-panneau">
        <div class="bo-panneau-tete"><h3>Publication</h3></div>

        <div class="bo-champ">
          <label for="statut">Statut</label>
          <select id="statut" name="statut">
            <option value="brouillon" @selected(old('statut', $article->statut) === 'brouillon')>Brouillon</option>
            <option value="publie" @selected(old('statut', $article->statut) === 'publie')>Publié</option>
          </select>
        </div>

        <div class="bo-champ">
          <label for="publie_le">Date de publication</label>
          <input type="datetime-local" id="publie_le" name="publie_le"
                 value="{{ old('publie_le', $article->publie_le?->format('Y-m-d\TH:i')) }}">
          <span class="sous">Une date future planifie la parution.</span>
        </div>

        <div class="bo-champ">
          <label for="slug">Slug (vide = auto)</label>
          <input type="text" id="slug" name="slug" value="{{ old('slug', $article->slug) }}">
        </div>

        <div class="bo-champ">
          <label for="categorie">Catégorie</label>
          <input type="text" id="categorie" name="categorie" value="{{ old('categorie', $article->categorie) }}">
        </div>

        <div class="bo-champ">
          <label for="image_une">Image à la une</label>
          <input type="file" id="image_une" name="image_une" accept="image/jpeg,image/png,image/webp">
          @if($article->image_une)
            <span class="sous">Actuelle : {{ basename($article->image_une) }}</span>
          @endif
        </div>
      </div>

      @include('admin.partials.champs-seo', ['entite' => $article])

      <button type="submit" class="bo-btn bo-btn-cobalt" style="width:100%;justify-content:center">Enregistrer</button>
    </div>
  </div>
</form>
@endsection
