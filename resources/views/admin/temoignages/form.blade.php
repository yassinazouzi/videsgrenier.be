@extends('admin.layout')
@section('titre', $temoignage->exists ? 'Éditer : '.$temoignage->auteur : 'Nouveau témoignage')

@section('actions')
  <a href="{{ route('admin.temoignages.index') }}" class="bo-btn">Retour</a>
@endsection

@section('contenu')
<form method="POST" action="{{ $temoignage->exists ? route('admin.temoignages.update', $temoignage) : route('admin.temoignages.store') }}">
  @csrf
  @if($temoignage->exists) @method('PUT') @endif

  <div class="bo-cols">
    <div>
      <div class="bo-panneau">
        <div class="bo-panneau-tete"><h3>L’avis</h3></div>

        <div class="bo-champ">
          <label for="auteur">Auteur *</label>
          <input type="text" id="auteur" name="auteur" required value="{{ old('auteur', $temoignage->auteur) }}" placeholder="Sophie D.">
        </div>

        <div class="bo-champ">
          <label for="texte">Témoignage *</label>
          <textarea id="texte" name="texte" rows="6" required maxlength="2000">{{ old('texte', $temoignage->texte) }}</textarea>
        </div>
      </div>
    </div>

    <div>
      <div class="bo-panneau">
        <div class="bo-panneau-tete"><h3>Détails</h3></div>

        <div class="bo-champ">
          <label for="commune">Commune</label>
          <select id="commune" name="commune">
            <option value="">—</option>
            @foreach($communes as $c)
              <option value="{{ $c->nom }}" @selected(old('commune', $temoignage->commune) === $c->nom)>{{ $c->nom }}</option>
            @endforeach
          </select>
        </div>

        <div class="bo-champ">
          <label for="note">Note</label>
          <select id="note" name="note">
            @for($i = 5; $i >= 1; $i--)
              <option value="{{ $i }}" @selected((int) old('note', $temoignage->note ?? 5) === $i)>{{ str_repeat('★', $i) }}</option>
            @endfor
          </select>
        </div>

        <div class="bo-champ">
          <label for="ordre">Ordre d’affichage</label>
          <input type="number" id="ordre" name="ordre" min="0" value="{{ old('ordre', $temoignage->ordre ?? 0) }}">
        </div>

        <label style="display:flex;align-items:center;gap:10px;font-size:14px">
          <span class="bo-toggle">
            <input type="checkbox" name="publie" value="1" @checked(old('publie', $temoignage->publie ?? true))>
            <span class="piste"></span>
          </span>
          Publié
        </label>
      </div>

      <button type="submit" class="bo-btn bo-btn-cobalt" style="width:100%;justify-content:center">Enregistrer</button>
    </div>
  </div>
</form>
@endsection
