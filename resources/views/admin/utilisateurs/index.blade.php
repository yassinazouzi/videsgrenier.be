@extends('admin.layout')
@section('titre', 'Utilisateurs')

@section('actions')
  <a href="{{ route('admin.utilisateurs.create') }}" class="bo-btn bo-btn-cobalt">Nouveau compte</a>
@endsection

@section('contenu')
<div class="bo-panneau">
  <table class="bo-table">
    <thead><tr><th>Nom</th><th>E-mail</th><th>Rôle</th><th>Créé le</th><th></th></tr></thead>
    <tbody>
      @foreach($utilisateurs as $utilisateur)
        <tr>
          <td>
            <div class="titre-cell">{{ $utilisateur->nom }}</div>
            @if($utilisateur->is(auth()->user()))<div class="sous">c’est vous</div>@endif
          </td>
          <td class="sous">{{ $utilisateur->email }}</td>
          <td>
            <span class="badge {{ $utilisateur->estSuperAdmin() ? 'badge-nouveau' : '' }}">
              {{ $utilisateur->estSuperAdmin() ? 'Super-admin' : 'Éditeur' }}
            </span>
          </td>
          <td class="sous">{{ $utilisateur->cree_le?->format('d/m/Y') }}</td>
          <td style="display:flex;gap:8px">
            <a href="{{ route('admin.utilisateurs.edit', $utilisateur) }}" class="bo-btn bo-btn-sm">Éditer</a>
            @unless($utilisateur->is(auth()->user()))
              <form method="POST" action="{{ route('admin.utilisateurs.destroy', $utilisateur) }}"
                    onsubmit="return confirm('Supprimer ce compte ?')">
                @csrf @method('DELETE')
                <button class="bo-btn bo-btn-sm bo-btn-danger">Suppr.</button>
              </form>
            @endunless
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
