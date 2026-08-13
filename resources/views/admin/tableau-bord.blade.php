@extends('admin.layout')
@section('titre', 'Tableau de bord')

@section('actions')
  <a href="{{ route('admin.devis.index') }}" class="bo-btn bo-btn-cobalt">Voir les demandes</a>
@endsection

@section('contenu')

<div class="bo-kpis">
  <div class="bo-kpi">
    <div class="label">Nouvelles demandes</div>
    <div class="valeur">{{ $nouveaux }}</div>
    <div class="delta">à traiter</div>
  </div>
  <div class="bo-kpi">
    <div class="label">En cours</div>
    <div class="valeur">{{ $enCours }}</div>
    <div class="delta">contactées ou devis envoyé</div>
  </div>
  <div class="bo-kpi">
    <div class="label">Taux de conversion</div>
    <div class="valeur">{{ $tauxConversion !== null ? $tauxConversion.'%' : '—' }}</div>
    <div class="delta">gagnées / dossiers clos</div>
  </div>
  <div class="bo-kpi">
    <div class="label">Reçues aujourd’hui</div>
    <div class="valeur">{{ $aujourdhui }}</div>
    <div class="delta">{{ now()->translatedFormat('d/m/Y') }}</div>
  </div>
</div>

<div class="bo-cols">
  <div>
    <div class="bo-panneau">
      <div class="bo-panneau-tete">
        <h3>Dernières demandes</h3>
        <a href="{{ route('admin.devis.index') }}" class="bo-btn bo-btn-sm">Tout voir</a>
      </div>

      @if($derniers->isEmpty())
        <p class="muet">Aucune demande pour l’instant.</p>
      @else
        <table class="bo-table">
          <thead><tr><th>Client</th><th>Prestation</th><th>Commune</th><th>Statut</th><th></th></tr></thead>
          <tbody>
            @foreach($derniers as $d)
              <tr>
                <td>
                  <div class="titre-cell">{{ $d->nom }}</div>
                  <div class="sous">{{ $d->cree_le?->format('d/m/Y H:i') }}</div>
                </td>
                <td>{{ $d->prestation ?: '—' }}</td>
                <td>{{ $d->commune ?: '—' }}</td>
                <td>@include('admin.devis.badge', ['statut' => $d->statut])</td>
                <td><a href="{{ route('admin.devis.show', $d) }}" class="bo-btn bo-btn-sm">Ouvrir</a></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
  </div>

  <div>
    <div class="bo-panneau">
      <div class="bo-panneau-tete"><h3>Pipeline</h3></div>
      @foreach(\App\Models\Devis::STATUTS as $statut)
        @php $total = $parStatut[$statut] ?? 0; $max = max($parStatut->max() ?: 1, 1); @endphp
        <div style="margin-bottom:12px">
          <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:5px">
            <span>@include('admin.devis.badge', ['statut' => $statut])</span>
            <strong>{{ $total }}</strong>
          </div>
          <div style="height:6px;background:var(--bo-fond);border-radius:999px;overflow:hidden">
            <div style="height:100%;width:{{ round($total / $max * 100) }}%;background:var(--cobalt)"></div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="bo-panneau">
      <div class="bo-panneau-tete"><h3>14 derniers jours</h3></div>
      @php $maxJour = max($parJour->max() ?: 1, 1); @endphp
      <div style="display:flex;align-items:flex-end;gap:4px;height:90px">
        @for($i = 13; $i >= 0; $i--)
          @php
            $jour = now()->subDays($i)->toDateString();
            $valeur = $parJour[$jour] ?? 0;
          @endphp
          <div title="{{ $jour }} : {{ $valeur }}"
               style="flex:1;background:{{ $valeur ? 'var(--cobalt)' : 'var(--bo-ligne)' }};border-radius:3px 3px 0 0;height:{{ max(round($valeur / $maxJour * 100), 4) }}%"></div>
        @endfor
      </div>
      <div class="sous" style="margin-top:8px;display:flex;justify-content:space-between">
        <span>{{ now()->subDays(13)->format('d/m') }}</span><span>{{ now()->format('d/m') }}</span>
      </div>
    </div>

    <div class="bo-panneau">
      <div class="bo-panneau-tete"><h3>Chiffre gagné</h3></div>
      <div class="valeur" style="font-family:var(--display);font-weight:800;font-size:30px;color:#fff">
        {{ number_format((float) $caGagne, 2, ',', ' ') }} €
      </div>
      <div class="sous">cumul des devis gagnés</div>
    </div>
  </div>
</div>

@endsection
