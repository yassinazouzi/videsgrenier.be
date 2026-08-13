@php
  $services ??= \App\Models\Service::actifs()->get();
  $communes ??= \App\Models\Commune::actives()->get();
  $communePreselect ??= null;
  $prestationPreselect ??= null;
@endphp

<form method="POST" action="{{ route('devis.store') }}">
  @csrf
  <input type="hidden" name="source" value="{{ Route::currentRouteName() }}">

  {{-- Honeypot : invisible pour l'humain, rempli par les robots --}}
  <div style="position:absolute;left:-9999px" aria-hidden="true">
    <label for="societe">Société</label>
    <input type="text" id="societe" name="societe" tabindex="-1" autocomplete="off">
  </div>

  @if($errors->any())
    <div class="champ" role="alert">
      <ul style="color:var(--rouge);font-size:14px">
        @foreach($errors->all() as $erreur)<li>{{ $erreur }}</li>@endforeach
      </ul>
    </div>
  @endif

  <div class="champ-2">
    <div class="champ">
      <label for="nom">Nom *</label>
      <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required autocomplete="name">
    </div>
    <div class="champ">
      <label for="telephone">Téléphone *</label>
      <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}" required autocomplete="tel">
    </div>
  </div>

  <div class="champ">
    <label for="email">E-mail</label>
    <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email">
  </div>

  <div class="champ-2">
    <div class="champ">
      <label for="prestation">Prestation</label>
      <select id="prestation" name="prestation">
        <option value="">Choisir…</option>
        @foreach($services as $s)
          <option value="{{ $s->titre }}" @selected(old('prestation', $prestationPreselect) === $s->titre)>{{ $s->titre }}</option>
        @endforeach
      </select>
    </div>
    <div class="champ">
      <label for="commune">Commune</label>
      <input type="text" id="commune" name="commune" list="communes-liste" autocomplete="off"
             value="{{ old('commune', $communePreselect) }}" placeholder="Tapez pour chercher…">
      <datalist id="communes-liste">
        @foreach($communes as $c)
          <option value="{{ $c->nom }}">
        @endforeach
      </datalist>
    </div>
  </div>

  <div class="champ">
    <label for="volume_estime">Volume estimé</label>
    <input type="text" id="volume_estime" name="volume_estime" value="{{ old('volume_estime') }}"
           placeholder="Studio, 2 chambres, cave…">
  </div>

  <div class="champ">
    <label for="message">Votre message</label>
    <textarea id="message" name="message" placeholder="Étage, ascenseur, délai souhaité…">{{ old('message') }}</textarea>
  </div>

  <button type="submit" class="btn btn-cobalt btn-bloc">Recevoir mon devis gratuit</button>
  <p class="muet" style="font-size:12.5px;margin-top:10px;text-align:center">
    Réponse sous 24h · Sans engagement · Vos données ne sont jamais revendues
  </p>
</form>
