@extends('admin.layout')
@section('titre', $realisation->exists ? 'Éditer : '.$realisation->titre : 'Nouvelle réalisation')

@section('actions')
  <a href="{{ route('admin.realisations.index') }}" class="bo-btn">Retour</a>
@endsection

@section('contenu')
<form method="POST" enctype="multipart/form-data"
      action="{{ $realisation->exists ? route('admin.realisations.update', $realisation) : route('admin.realisations.store') }}">
  @csrf
  @if($realisation->exists) @method('PUT') @endif

  <div class="bo-cols">
    <div>
      <div class="bo-panneau">
        <div class="bo-panneau-tete"><h3>Le chantier</h3></div>

        <div class="bo-champ">
          <label for="titre">Titre *</label>
          <input type="text" id="titre" name="titre" required
                 value="{{ old('titre', $realisation->titre) }}" placeholder="Appartement 2 ch. — Ixelles">
        </div>

        <div class="bo-champ">
          <label for="description">Description</label>
          <textarea id="description" name="description" rows="6">{{ old('description', $realisation->description) }}</textarea>
        </div>

        <div class="bo-champ">
          <label for="duree">Durée</label>
          <input type="text" id="duree" name="duree" value="{{ old('duree', $realisation->duree) }}" placeholder="1 journée">
        </div>
      </div>

      <div class="bo-panneau">
        <div class="bo-panneau-tete"><h3>Photos avant / après</h3></div>

        <div class="bo-champ">
          <label for="photo_avant">Photo « avant » (JPG, PNG ou WebP — 5 Mo max)</label>
          <input type="file" id="photo_avant" name="photo_avant" accept="image/jpeg,image/png,image/webp">
          @if($realisation->photo_avant)
            <span class="sous">Actuelle : {{ basename($realisation->photo_avant) }}</span>
          @endif
        </div>

        <div class="bo-champ">
          <label for="photo_apres">Photo « après »</label>
          <input type="file" id="photo_apres" name="photo_apres" accept="image/jpeg,image/png,image/webp">
          @if($realisation->photo_apres)
            <span class="sous">Actuelle : {{ basename($realisation->photo_apres) }}</span>
          @endif
        </div>

        <p class="sous">
          Les fichiers sont renommés à partir du slug pour le SEO images
          (ex. « appartement-2ch-ixelles-avant.jpg »). Le nom d’origine n’est jamais conservé.
        </p>
      </div>
    </div>

    <div>
      <div class="bo-panneau">
        <div class="bo-panneau-tete"><h3>Classement</h3></div>

        <div class="bo-champ">
          <label for="slug">Slug (vide = auto)</label>
          <input type="text" id="slug" name="slug" value="{{ old('slug', $realisation->slug) }}">
        </div>

        <div class="bo-champ">
          <label for="commune">Commune</label>
          <select id="commune" name="commune">
            <option value="">—</option>
            @foreach($communes as $c)
              <option value="{{ $c->nom }}" @selected(old('commune', $realisation->commune) === $c->nom)>{{ $c->nom }}</option>
            @endforeach
          </select>
        </div>

        <div class="bo-champ">
          <label for="type_presta">Type de prestation</label>
          <select id="type_presta" name="type_presta">
            <option value="">—</option>
            @foreach($services as $s)
              <option value="{{ $s->titre }}" @selected(old('type_presta', $realisation->type_presta) === $s->titre)>{{ $s->titre }}</option>
            @endforeach
          </select>
        </div>

        <label style="display:flex;align-items:center;gap:10px;font-size:14px">
          <span class="bo-toggle">
            <input type="checkbox" name="publie" value="1" @checked(old('publie', $realisation->publie ?? true))>
            <span class="piste"></span>
          </span>
          Publiée
        </label>
      </div>

      <button type="submit" class="bo-btn bo-btn-cobalt" style="width:100%;justify-content:center">Enregistrer</button>
    </div>
  </div>
</form>
@endsection
