@extends('admin.layout')
@section('titre', 'Témoignages')

@section('actions')
  <a href="{{ route('admin.temoignages.create') }}" class="bo-btn bo-btn-cobalt">Nouveau témoignage</a>
@endsection

@section('contenu')
<div class="bo-panneau">
  <table class="bo-table">
    <thead><tr><th>Ordre</th><th>Auteur</th><th>Note</th><th>Avis</th><th>État</th><th></th></tr></thead>
    <tbody>
      @forelse($temoignages as $temoignage)
        <tr>
          <td class="sous">{{ $temoignage->ordre }}</td>
          <td>
            <div class="titre-cell">{{ $temoignage->auteur }}</div>
            <div class="sous">{{ $temoignage->commune }}</div>
          </td>
          <td style="color:var(--etiquette)">{{ str_repeat('★', $temoignage->note) }}</td>
          <td class="sous">{{ Str::limit($temoignage->texte, 70) }}</td>
          <td>
            <span class="badge {{ $temoignage->publie ? 'badge-publie' : 'badge-perdu' }}">
              {{ $temoignage->publie ? 'Publié' : 'Masqué' }}
            </span>
          </td>
          <td style="display:flex;gap:8px">
            <a href="{{ route('admin.temoignages.edit', $temoignage) }}" class="bo-btn bo-btn-sm">Éditer</a>
            <form method="POST" action="{{ route('admin.temoignages.destroy', $temoignage) }}"
                  onsubmit="return confirm('Supprimer ce témoignage ?')">
              @csrf @method('DELETE')
              <button class="bo-btn bo-btn-sm bo-btn-danger">Suppr.</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="muet">Aucun témoignage.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
