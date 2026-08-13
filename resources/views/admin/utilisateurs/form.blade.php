@extends('admin.layout')
@section('titre', $utilisateur->exists ? 'Compte : '.$utilisateur->nom : 'Nouveau compte')

@section('actions')
  <a href="{{ route('admin.utilisateurs.index') }}" class="bo-btn">Retour</a>
@endsection

@section('contenu')
<form method="POST" action="{{ $utilisateur->exists ? route('admin.utilisateurs.update', $utilisateur) : route('admin.utilisateurs.store') }}">
  @csrf
  @if($utilisateur->exists) @method('PUT') @endif

  <div class="bo-cols">
    <div>
      <div class="bo-panneau">
        <div class="bo-panneau-tete"><h3>Identité</h3></div>

        <div class="bo-champ">
          <label for="nom">Nom *</label>
          <input type="text" id="nom" name="nom" required value="{{ old('nom', $utilisateur->nom) }}">
        </div>

        <div class="bo-champ">
          <label for="email">E-mail *</label>
          <input type="email" id="email" name="email" required value="{{ old('email', $utilisateur->email) }}" autocomplete="username">
        </div>

        <div class="bo-champ">
          <label for="role">Rôle</label>
          <select id="role" name="role">
            <option value="editeur" @selected(old('role', $utilisateur->role) === 'editeur')>Éditeur — contenu uniquement</option>
            <option value="super_admin" @selected(old('role', $utilisateur->role) === 'super_admin')>Super-admin — accès total</option>
          </select>
        </div>
      </div>
    </div>

    <div>
      <div class="bo-panneau">
        <div class="bo-panneau-tete"><h3>Mot de passe</h3></div>

        <div class="bo-champ">
          <label for="mot_de_passe">{{ $utilisateur->exists ? 'Nouveau mot de passe' : 'Mot de passe *' }}</label>
          <input type="password" id="mot_de_passe" name="mot_de_passe"
                 @required(! $utilisateur->exists) autocomplete="new-password">
          <span class="sous">12 caractères minimum.</span>
        </div>

        <div class="bo-champ">
          <label for="mot_de_passe_confirmation">Confirmation</label>
          <input type="password" id="mot_de_passe_confirmation" name="mot_de_passe_confirmation"
                 @required(! $utilisateur->exists) autocomplete="new-password">
        </div>

        @if($utilisateur->exists)
          <p class="sous">Laissez vide pour conserver le mot de passe actuel.</p>
        @endif
      </div>

      <button type="submit" class="bo-btn bo-btn-cobalt" style="width:100%;justify-content:center">Enregistrer</button>
    </div>
  </div>
</form>
@endsection
