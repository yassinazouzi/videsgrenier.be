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
    <div class="bo-panneau-tete"><h3>Ajouter des photos ou des vidéos</h3></div>
    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.galeries.photos.ajouter', $galerie) }}">
      @csrf
      <div class="bo-champ">
        <label for="photos">Sélection multiple (30 fichiers max, 50 Mo chacun)</label>
        <input type="file" id="photos" name="photos[]" multiple required
               accept="image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime">
        <span class="sous">Photos : JPG, PNG, WebP · Vidéos : MP4, WebM, MOV</span>
      </div>
      <button type="submit" class="bo-btn bo-btn-cobalt">Téléverser</button>
    </form>
  </div>

  <div class="bo-panneau">
    <div class="bo-panneau-tete">
      <h3>Contenu de la galerie ({{ $galerie->photos->count() }})</h3>
    </div>

    @if($galerie->photos->isEmpty())
      <p class="muet">Aucun média pour l’instant.</p>
    @else
      <p class="sous" style="margin-bottom:14px">
        Glissez les lignes par la poignée <strong>⠿</strong> pour changer l’ordre d’affichage,
        puis enregistrez. L’ordre défini ici est celui vu par les visiteurs.
      </p>

      <form method="POST" action="{{ route('admin.galeries.photos.maj', $galerie) }}" id="form-medias">
        @csrf @method('PUT')
        <table class="bo-table" id="table-medias">
          <thead>
            <tr><th style="width:34px"></th><th>Aperçu</th><th>Type</th><th>Texte alternatif (SEO)</th><th></th></tr>
          </thead>
          <tbody id="corps-medias">
            @foreach($galerie->photos as $photo)
              <tr draggable="true" data-id="{{ $photo->id }}">
                <td class="poignee" style="cursor:grab;text-align:center;font-size:18px;color:var(--bo-texte-2)"
                    title="Glisser pour réordonner">⠿</td>
                <td>
                  @if($photo->estVideo())
                    <video src="{{ asset($photo->url) }}" muted preload="metadata"
                           style="width:84px;height:56px;object-fit:cover;border-radius:6px;background:#000"></video>
                  @else
                    <img src="{{ asset($photo->url) }}" alt=""
                         style="width:84px;height:56px;object-fit:cover;border-radius:6px">
                  @endif
                </td>
                <td>
                  <span class="badge {{ $photo->estVideo() ? 'badge-contacte' : 'badge-nouveau' }}">
                    {{ $photo->estVideo() ? 'Vidéo' : 'Photo' }}
                  </span>
                </td>
                <td>
                  <input type="text" name="photos[{{ $photo->id }}][alt]" value="{{ $photo->alt }}"
                         style="width:100%;background:var(--bo-fond);border:1px solid var(--bo-ligne);border-radius:8px;padding:8px 10px;color:var(--bo-texte)">
                  {{-- Rempli par le glisser-déposer ; reste correct sans JavaScript. --}}
                  <input type="hidden" name="photos[{{ $photo->id }}][ordre]" value="{{ $photo->ordre }}" class="champ-ordre">
                </td>
                <td>
                  <button type="submit" form="suppr-{{ $photo->id }}" class="bo-btn bo-btn-sm bo-btn-danger">Suppr.</button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <button type="submit" class="bo-btn bo-btn-cobalt" style="margin-top:16px">Enregistrer l’ordre et les textes</button>
      </form>

      {{-- Formulaires de suppression hors du formulaire principal : un form ne peut pas en contenir un autre. --}}
      @foreach($galerie->photos as $photo)
        <form id="suppr-{{ $photo->id }}" method="POST"
              action="{{ route('admin.galeries.photos.supprimer', [$galerie, $photo]) }}"
              onsubmit="return confirm('Supprimer ce média ?')">
          @csrf @method('DELETE')
        </form>
      @endforeach

      <script>
      (function () {
        var corps = document.getElementById('corps-medias');
        if (!corps) return;
        var ligneTiree = null;

        function renumeroter() {
          corps.querySelectorAll('tr').forEach(function (tr, index) {
            var champ = tr.querySelector('.champ-ordre');
            if (champ) champ.value = index + 1;
          });
        }

        corps.addEventListener('dragstart', function (e) {
          var tr = e.target.closest('tr');
          if (!tr) return;
          ligneTiree = tr;
          tr.style.opacity = '.4';
          e.dataTransfer.effectAllowed = 'move';
        });

        corps.addEventListener('dragend', function () {
          if (ligneTiree) ligneTiree.style.opacity = '';
          ligneTiree = null;
          renumeroter();
        });

        corps.addEventListener('dragover', function (e) {
          e.preventDefault();
          var cible = e.target.closest('tr');
          if (!cible || !ligneTiree || cible === ligneTiree) return;

          // Insère avant ou après selon que le curseur est au-dessus ou
          // au-dessous du milieu de la ligne survolée.
          var rect = cible.getBoundingClientRect();
          var apres = (e.clientY - rect.top) > (rect.height / 2);
          corps.insertBefore(ligneTiree, apres ? cible.nextSibling : cible);
        });
      })();
      </script>
    @endif
  </div>
@endif

@endsection
