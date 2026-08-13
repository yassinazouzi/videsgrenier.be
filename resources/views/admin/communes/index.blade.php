@extends('admin.layout')
@section('titre', 'Communes')

@section('contenu')

<div class="bo-panneau" style="border-color:#3346C8">
  <strong style="color:var(--bo-badge-nouveau)">Le levier SEO n°1.</strong>
  <p class="sous" style="margin-top:6px">
    Chaque page commune doit avoir un texte <em>unique</em> : quartiers, types de logements,
    contraintes d’accès, exemples de chantiers. Dupliquer le même paragraphe sur les 19 pages
    les ferait toutes plafonner dans les résultats.
  </p>
</div>

<div class="bo-panneau">
  <table class="bo-table">
    <thead><tr><th>Commune</th><th>URL</th><th>Contenu unique</th><th>SEO</th><th>État</th><th></th></tr></thead>
    <tbody>
      @foreach($communes as $commune)
        @php $mots = str_word_count(strip_tags($commune->intro ?? '')); @endphp
        <tr>
          <td>
            <div class="titre-cell">{{ $commune->nom }}</div>
            <div class="sous">{{ $commune->code_postal }}</div>
          </td>
          <td class="sous">/debarras/{{ $commune->slug }}</td>
          <td>
            @if($mots === 0)
              <span class="badge badge-perdu">Vide</span>
            @elseif($mots < 150)
              <span class="badge badge-contacte">{{ $mots }} mots</span>
            @else
              <span class="badge badge-gagne">{{ $mots }} mots</span>
            @endif
          </td>
          <td>
            <span class="badge {{ $commune->meta_title ? 'badge-gagne' : 'badge-contacte' }}">
              {{ $commune->meta_title ? 'Renseigné' : 'Auto' }}
            </span>
          </td>
          <td>
            <span class="badge {{ $commune->actif ? 'badge-publie' : 'badge-perdu' }}">
              {{ $commune->actif ? 'En ligne' : 'Masquée' }}
            </span>
          </td>
          <td><a href="{{ route('admin.communes.edit', $commune) }}" class="bo-btn bo-btn-sm">Éditer</a></td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
