@extends('admin.layout')
@section('titre', 'Demandes de devis')

@section('actions')
  <a href="{{ route('admin.devis.export', request()->only('statut')) }}" class="bo-btn">Export CSV</a>
@endsection

@section('contenu')

<div class="bo-panneau">
  <form method="GET" style="display:grid;grid-template-columns:1fr 1fr 1.4fr auto;gap:12px;align-items:end">
    <div class="bo-champ" style="margin:0">
      <label for="statut">Statut</label>
      <select id="statut" name="statut">
        <option value="">Tous</option>
        @foreach(\App\Models\Devis::STATUTS as $statut)
          <option value="{{ $statut }}" @selected(request('statut') === $statut)>{{ $statut }}</option>
        @endforeach
      </select>
    </div>
    <div class="bo-champ" style="margin:0">
      <label for="commune">Commune</label>
      <select id="commune" name="commune">
        <option value="">Toutes</option>
        @foreach($communes as $c)
          <option value="{{ $c->nom }}" @selected(request('commune') === $c->nom)>{{ $c->nom }}</option>
        @endforeach
      </select>
    </div>
    <div class="bo-champ" style="margin:0">
      <label for="recherche">Nom, téléphone ou e-mail</label>
      <input type="search" id="recherche" name="recherche" value="{{ request('recherche') }}">
    </div>
    <button type="submit" class="bo-btn bo-btn-cobalt">Filtrer</button>
  </form>
</div>

<div class="bo-panneau">
  @if($devis->isEmpty())
    <p class="muet">Aucune demande ne correspond à ces critères.</p>
  @else
    <table class="bo-table">
      <thead>
        <tr><th>Client</th><th>Prestation</th><th>Commune</th><th>Canal</th><th>Statut</th><th>Montant</th><th></th></tr>
      </thead>
      <tbody>
        @foreach($devis as $d)
          <tr>
            <td>
              <div class="titre-cell">{{ $d->nom }}</div>
              <div class="sous">{{ $d->telephone }} · {{ $d->cree_le?->format('d/m/Y H:i') }}</div>
            </td>
            <td>{{ $d->prestation ?: '—' }}</td>
            <td>{{ $d->commune ?: '—' }}</td>
            <td class="sous">{{ $d->canal }}</td>
            <td>@include('admin.devis.badge', ['statut' => $d->statut])</td>
            <td class="sous">{{ $d->montant_devis ? number_format((float) $d->montant_devis, 2, ',', ' ').' €' : '—' }}</td>
            <td><a href="{{ route('admin.devis.show', $d) }}" class="bo-btn bo-btn-sm">Ouvrir</a></td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div style="margin-top:18px">{{ $devis->links() }}</div>
  @endif
</div>

@endsection
