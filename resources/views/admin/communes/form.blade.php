@extends('admin.layout')
@section('titre', 'Page : '.$commune->nom)

@section('actions')
  <a href="{{ route('commune.show', $commune) }}" target="_blank" rel="noopener" class="bo-btn">Voir la page</a>
  <a href="{{ route('admin.communes.index') }}" class="bo-btn">Retour</a>
@endsection

@section('contenu')
<form method="POST" action="{{ route('admin.communes.update', $commune) }}">
  @csrf @method('PUT')

  <div class="bo-cols">
    <div>
      <div class="bo-panneau">
        <div class="bo-panneau-tete">
          <h3>Contenu unique — {{ $commune->nom }}</h3>
          <span class="sous">/debarras/{{ $commune->slug }}</span>
        </div>

        <div class="bo-champ">
          <label for="intro">Texte de la page</label>
          <textarea id="intro" name="intro" rows="18"
                    placeholder="Quartiers desservis, types de logements, contraintes d'accès (escaliers étroits, pas d'ascenseur), exemples de chantiers réalisés, spécificités locales…">{{ old('intro', $commune->intro) }}</textarea>
        </div>

        <p class="sous">
          Visez 200 à 400 mots réellement spécifiques à {{ $commune->nom }}.
          Citez des quartiers et des rues : c’est ce qui distingue cette page des 18 autres.
        </p>
      </div>
    </div>

    <div>
      <div class="bo-panneau">
        <div class="bo-panneau-tete"><h3>Publication</h3></div>
        <label style="display:flex;align-items:center;gap:10px;font-size:14px">
          <span class="bo-toggle">
            <input type="checkbox" name="actif" value="1" @checked(old('actif', $commune->actif))>
            <span class="piste"></span>
          </span>
          Page en ligne
        </label>
        <p class="sous" style="margin-top:10px">
          Masquée, la page renvoie une 404 et sort du sitemap.
        </p>
      </div>

      @include('admin.partials.champs-seo', ['entite' => $commune])

      <button type="submit" class="bo-btn bo-btn-cobalt" style="width:100%;justify-content:center">Enregistrer</button>
    </div>
  </div>
</form>
@endsection
