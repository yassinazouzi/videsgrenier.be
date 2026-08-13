@extends('admin.layout')
@section('titre', 'Demande #'.$devis->id)

@section('actions')
  <a href="{{ route('admin.devis.index') }}" class="bo-btn">Retour à la liste</a>
@endsection

@section('contenu')

<div class="bo-cols">
  <div>
    <div class="bo-panneau">
      <div class="bo-panneau-tete">
        <h3>{{ $devis->nom }}</h3>
        @include('admin.devis.badge', ['statut' => $devis->statut])
      </div>

      <table class="bo-table">
        <tbody>
          <tr><th>Téléphone</th><td><a href="tel:{{ preg_replace('/\s+/', '', $devis->telephone) }}">{{ $devis->telephone }}</a></td></tr>
          <tr><th>E-mail</th><td>{{ $devis->email ? '' : '—' }}@if($devis->email)<a href="mailto:{{ $devis->email }}">{{ $devis->email }}</a>@endif</td></tr>
          <tr><th>Prestation</th><td>{{ $devis->prestation ?: '—' }}</td></tr>
          <tr><th>Commune</th><td>{{ $devis->commune ?: '—' }}</td></tr>
          <tr><th>Volume estimé</th><td>{{ $devis->volume_estime ?: '—' }}</td></tr>
          <tr><th>Canal</th><td>{{ $devis->canal }}</td></tr>
          <tr><th>Page d’origine</th><td>{{ $devis->source ?: '—' }}</td></tr>
          <tr><th>Reçue le</th><td>{{ $devis->cree_le?->format('d/m/Y à H:i') }}</td></tr>
        </tbody>
      </table>

      @if($devis->message)
        <div style="margin-top:18px">
          <div class="sous" style="margin-bottom:6px">MESSAGE DU CLIENT</div>
          <p>{{ $devis->message }}</p>
        </div>
      @endif
    </div>

    <div class="bo-panneau">
      <div class="bo-panneau-tete"><h3>Répondre</h3></div>
      <div style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="bo-btn bo-btn-cobalt" href="tel:{{ preg_replace('/\s+/', '', $devis->telephone) }}">Appeler</a>
        @if($devis->email)
          <a class="bo-btn" href="mailto:{{ $devis->email }}?subject={{ rawurlencode('Votre devis — Videsgrenier.be') }}">E-mail</a>
        @endif
        <a class="bo-btn" target="_blank" rel="noopener"
           href="https://wa.me/{{ preg_replace('/\D/', '', $devis->telephone) }}?text={{ rawurlencode('Bonjour '.$devis->nom.', suite à votre demande de devis sur Videsgrenier.be…') }}">
          WhatsApp
        </a>
      </div>
      <p class="sous" style="margin-top:10px">
        Le lien WhatsApp suppose un numéro belge au format international. Vérifiez-le avant l’envoi.
      </p>
    </div>
  </div>

  <div>
    <div class="bo-panneau">
      <div class="bo-panneau-tete"><h3>Suivi</h3></div>
      <form method="POST" action="{{ route('admin.devis.update', $devis) }}">
        @csrf
        @method('PUT')

        <div class="bo-champ">
          <label for="statut">Statut</label>
          <select id="statut" name="statut">
            @foreach(\App\Models\Devis::STATUTS as $statut)
              <option value="{{ $statut }}" @selected(old('statut', $devis->statut) === $statut)>{{ $statut }}</option>
            @endforeach
          </select>
        </div>

        <div class="bo-champ">
          <label for="montant_devis">Montant du devis (€)</label>
          <input type="number" step="0.01" min="0" id="montant_devis" name="montant_devis"
                 value="{{ old('montant_devis', $devis->montant_devis) }}">
        </div>

        <div class="bo-champ">
          <label for="note_interne">Note interne</label>
          <textarea id="note_interne" name="note_interne" rows="6">{{ old('note_interne', $devis->note_interne) }}</textarea>
        </div>

        <button type="submit" class="bo-btn bo-btn-cobalt" style="width:100%;justify-content:center">Enregistrer</button>
      </form>
    </div>

    <div class="bo-panneau">
      <div class="bo-panneau-tete"><h3>Zone sensible</h3></div>
      <form method="POST" action="{{ route('admin.devis.destroy', $devis) }}"
            onsubmit="return confirm('Supprimer définitivement cette demande ?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="bo-btn bo-btn-danger">Supprimer la demande</button>
      </form>
      <p class="sous" style="margin-top:10px">
        Suppression définitive — à utiliser pour une demande de droit à l’effacement (RGPD).
      </p>
    </div>
  </div>
</div>

@endsection
