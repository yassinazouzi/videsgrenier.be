@extends('admin.layout')
@section('titre', 'Services')

@section('actions')
  <a href="{{ route('admin.services.create') }}" class="bo-btn bo-btn-cobalt">Nouveau service</a>
@endsection

@section('contenu')
<div class="bo-panneau">
  <table class="bo-table">
    <thead><tr><th>Ordre</th><th>Service</th><th>Slug</th><th>État</th><th></th></tr></thead>
    <tbody>
      @forelse($services as $service)
        <tr>
          <td class="sous">{{ $service->ordre }}</td>
          <td>
            <div class="titre-cell">{{ $service->icone }} {{ $service->titre }}</div>
            <div class="sous">{{ Str::limit($service->extrait, 70) }}</div>
          </td>
          <td class="sous">/services/{{ $service->slug }}</td>
          <td>
            <span class="badge {{ $service->actif ? 'badge-publie' : 'badge-perdu' }}">
              {{ $service->actif ? 'Actif' : 'Masqué' }}
            </span>
          </td>
          <td style="display:flex;gap:8px">
            <a href="{{ route('admin.services.edit', $service) }}" class="bo-btn bo-btn-sm">Éditer</a>
            <form method="POST" action="{{ route('admin.services.destroy', $service) }}"
                  onsubmit="return confirm('Supprimer ce service ?')">
              @csrf @method('DELETE')
              <button class="bo-btn bo-btn-sm bo-btn-danger">Suppr.</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="muet">Aucun service.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
