@extends('admin.layout')
@section('titre', 'Galeries')

@section('actions')
  <a href="{{ route('admin.galeries.create') }}" class="bo-btn bo-btn-cobalt">Nouvelle galerie</a>
@endsection

@section('contenu')
<div class="bo-panneau">
  <table class="bo-table">
    <thead><tr><th>Galerie</th><th>Photos</th><th>État</th><th></th></tr></thead>
    <tbody>
      @forelse($galeries as $galerie)
        <tr>
          <td>
            <div class="titre-cell">{{ $galerie->titre }}</div>
            <div class="sous">/galerie/{{ $galerie->slug }}</div>
          </td>
          <td class="sous">{{ $galerie->photos_count }}</td>
          <td>
            <span class="badge {{ $galerie->publie ? 'badge-publie' : 'badge-perdu' }}">
              {{ $galerie->publie ? 'Publiée' : 'Brouillon' }}
            </span>
          </td>
          <td style="display:flex;gap:8px">
            <a href="{{ route('admin.galeries.edit', $galerie) }}" class="bo-btn bo-btn-sm">Éditer</a>
            <form method="POST" action="{{ route('admin.galeries.destroy', $galerie) }}"
                  onsubmit="return confirm('Supprimer cette galerie et toutes ses photos ?')">
              @csrf @method('DELETE')
              <button class="bo-btn bo-btn-sm bo-btn-danger">Suppr.</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="4" class="muet">Aucune galerie.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div style="margin-top:18px">{{ $galeries->links() }}</div>
</div>
@endsection
