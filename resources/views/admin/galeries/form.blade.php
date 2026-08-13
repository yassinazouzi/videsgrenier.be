@extends('admin.layout')
@section('titre', $galerie->exists ? 'Galerie : '.$galerie->titre : 'Nouvelle galerie')

@section('actions')
  <a href="{{ route('admin.galeries.index') }}" class="bo-btn">Retour</a>
@endsection

@section('contenu')

<form method="POST" enctype="multipart/form-data"
      action="{{ $galerie->exists ? route('admin.galeries.update', $galerie) : route('admin.galeries.store') }}">
  @csrf
  @if($galerie->exists) @method('PUT') @endif

  <div class="bo-cols">
    <div>
      <div class="bo-panneau">
        <div class="bo-panneau-tete"><h3>La galerie</h3></div>

        <div class="bo-champ">
          <label for="titre">Titre *</label>
          <input type="text" id="titre" name="titre" required value="{{ old('titre', $galerie->titre) }}">
        </div>

        <div class="bo-champ">
          <label for="description">Description</label>
          <textarea id="description" name="description" rows="4">{{ old('description', $galerie->description) }}</textarea>
        </div>
      </div>
    </div>

    <div>
      <div class="bo-panneau">
        <div class="bo-panneau-tete"><h3>Réglages</h3></div>

        <div class="bo-champ">
          <label for="slug">Slug (vide = auto)</label>
          <input type="text" id="slug" name="slug" value="{{ old('slug', $galerie->slug) }}">
        </div>

        <div class="bo-champ">
          <label for="couverture">Image de couverture</label>
          <input type="file" id="couverture" name="couverture" accept="image/jpeg,image/png,image/webp">
          @if($galerie->couverture)<span class="sous">Actuelle : {{ basename($galerie->couverture) }}</span>@endif
        </div>

        <label style="display:flex;align-items:center;gap:10px;font-size:14px">
          <span class="bo-toggle">
            <input type="checkbox" name="publie" value="1" @checked(old('publie', $galerie->publie ?? true))>
            <span class="piste"></span>
          </span>
          Publiée
        </label>
      </div>

      <button type="submit" class="bo-btn bo-btn-cobalt" style="width:100%;justify-content:center">Enregistrer</button>
    </div>
  </div>
</form>

@if($galerie->exists)
  <div class="bo-panneau" style="margin-top:20px">
    <div class="bo-panneau-tete"><h3>Ajouter des photos</h3></div>
    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.galeries.photos.ajouter', $galerie) }}">
      @csrf
      <div class="bo-champ">
        <label for="photos">Sélection multiple (30 fichiers max, 5 Mo chacun)</label>
        <input type="file" id="photos" name="photos[]" multiple required accept="image/jpeg,image/png,image/webp">
      </div>
      <button type="submit" class="bo-btn bo-btn-cobalt">Téléverser</button>
    </form>
  </div>

  <div class="bo-panneau">
    <div class="bo-panneau-tete">
      <h3>Photos ({{ $galerie->photos->count() }})</h3>
    </div>

    @if($galerie->photos->isEmpty())
      <p class="muet">Aucune photo pour l’instant.</p>
    @else
      <form method="POST" action="{{ route('admin.galeries.photos.maj', $galerie) }}">
        @csrf @method('PUT')
        <table class="bo-table">
          <thead><tr><th>Aperçu</th><th>Texte alternatif (SEO images)</th><th>Ordre</th><th></th></tr></thead>
          <tbody>
            @foreach($galerie->photos as $photo)
              <tr>
                <td><img src="{{ asset($photo->url) }}" alt="" style="width:84px;height:56px;object-fit:cover;border-radius:6px"></td>
                <td>
                  <input type="text" name="photos[{{ $photo->id }}][alt]" value="{{ $photo->alt }}"
                         style="width:100%;background:var(--bo-fond);border:1px solid var(--bo-ligne);border-radius:8px;padding:8px 10px;color:var(--bo-texte)">
                </td>
                <td>
                  <input type="number" name="photos[{{ $photo->id }}][ordre]" value="{{ $photo->ordre }}" min="0"
                         style="width:80px;background:var(--bo-fond);border:1px solid var(--bo-ligne);border-radius:8px;padding:8px 10px;color:var(--bo-texte)">
                </td>
                <td>
                  <button type="submit" form="suppr-{{ $photo->id }}" class="bo-btn bo-btn-sm bo-btn-danger">Suppr.</button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <button type="submit" class="bo-btn bo-btn-cobalt" style="margin-top:16px">Enregistrer les photos</button>
      </form>

      {{-- Formulaires de suppression hors du formulaire principal : un form ne peut pas en contenir un autre. --}}
      @foreach($galerie->photos as $photo)
        <form id="suppr-{{ $photo->id }}" method="POST"
              action="{{ route('admin.galeries.photos.supprimer', [$galerie, $photo]) }}"
              onsubmit="return confirm('Supprimer cette photo ?')">
          @csrf @method('DELETE')
        </form>
      @endforeach
    @endif
  </div>
@endif

@endsection
