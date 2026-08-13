@extends('admin.layout')
@section('titre', $service->exists ? 'Éditer : '.$service->titre : 'Nouveau service')

@section('actions')
  <a href="{{ route('admin.services.index') }}" class="bo-btn">Retour</a>
@endsection

@section('contenu')
<form method="POST" action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}">
  @csrf
  @if($service->exists) @method('PUT') @endif

  <div class="bo-cols">
    <div>
      <div class="bo-panneau">
        <div class="bo-panneau-tete"><h3>Contenu</h3></div>

        <div class="bo-champ">
          <label for="titre">Titre *</label>
          <input type="text" id="titre" name="titre" value="{{ old('titre', $service->titre) }}" required>
        </div>

        <div class="bo-champ">
          <label for="extrait">Extrait (affiché sur les cartes)</label>
          <input type="text" id="extrait" name="extrait" maxlength="255" value="{{ old('extrait', $service->extrait) }}">
        </div>

        <div class="bo-champ">
          <label for="contenu">Contenu de la page</label>
          <textarea id="contenu" name="contenu" rows="14">{{ old('contenu', $service->contenu) }}</textarea>
        </div>
      </div>
    </div>

    <div>
      <div class="bo-panneau">
        <div class="bo-panneau-tete"><h3>Réglages</h3></div>

        <div class="bo-champ">
          <label for="slug">Slug (laisser vide = auto)</label>
          <input type="text" id="slug" name="slug" value="{{ old('slug', $service->slug) }}">
        </div>

        <div class="bo-champ">
          <label for="icone">Icône (emoji)</label>
          <input type="text" id="icone" name="icone" maxlength="20" value="{{ old('icone', $service->icone) }}">
        </div>

        <div class="bo-champ">
          <label for="ordre">Ordre d’affichage</label>
          <input type="number" id="ordre" name="ordre" min="0" max="255" value="{{ old('ordre', $service->ordre ?? 0) }}">
        </div>

        <label style="display:flex;align-items:center;gap:10px;font-size:14px">
          <span class="bo-toggle">
            <input type="checkbox" name="actif" value="1" @checked(old('actif', $service->actif ?? true))>
            <span class="piste"></span>
          </span>
          Service actif
        </label>
      </div>

      @include('admin.partials.champs-seo', ['entite' => $service])

      <button type="submit" class="bo-btn bo-btn-cobalt" style="width:100%;justify-content:center">Enregistrer</button>
    </div>
  </div>
</form>
@endsection
