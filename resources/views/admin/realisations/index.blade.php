@extends('admin.layout')
@section('titre', 'Réalisations')

@section('actions')
  <a href="{{ route('admin.realisations.create') }}" class="bo-btn bo-btn-cobalt">Nouvelle réalisation</a>
@endsection

@section('contenu')
<div class="bo-panneau">
  <table class="bo-table">
    <thead><tr><th>Chantier</th><th>Commune</th><th>Photos</th><th>État</th><th></th></tr></thead>
    <tbody>
      @forelse($realisations as $realisation)
        <tr>
          <td>
            <div class="titre-cell">{{ $realisation->titre }}</div>
            <div class="sous">{{ $realisation->type_presta }} · {{ $realisation->duree }}</div>
          </td>
          <td>{{ $realisation->commune ?: '—' }}</td>
          <td class="sous">
            {{ $realisation->photo_avant ? 'avant ✓' : 'avant ✗' }} ·
            {{ $realisation->photo_apres ? 'après ✓' : 'après ✗' }}
          </td>
          <td>
            <span class="badge {{ $realisation->publie ? 'badge-publie' : 'badge-perdu' }}">
              {{ $realisation->publie ? 'Publiée' : 'Brouillon' }}
            </span>
          </td>
          <td style="display:flex;gap:8px">
            <a href="{{ route('admin.realisations.edit', $realisation) }}" class="bo-btn bo-btn-sm">Éditer</a>
            <form method="POST" action="{{ route('admin.realisations.destroy', $realisation) }}"
                  onsubmit="return confirm('Supprimer cette réalisation et ses photos ?')">
              @csrf @method('DELETE')
              <button class="bo-btn bo-btn-sm bo-btn-danger">Suppr.</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="muet">Aucune réalisation.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div style="margin-top:18px">{{ $realisations->links() }}</div>
</div>
@endsection
