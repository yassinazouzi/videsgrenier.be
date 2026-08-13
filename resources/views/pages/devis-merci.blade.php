@extends('layouts.site')

@section('titre', 'Merci — votre demande de devis est enregistrée')
@section('noindex', true)

@push('scripts')
<script>
// La conversion ne part qu'une fois gtag chargé, donc uniquement si l'audience a été acceptée.
(function () {
  function envoyer() {
    if (typeof window.gtag !== 'function') return;
    window.gtag('event', 'generate_lead', {
      event_category: 'devis',
      event_label: @json(session('devis_source', 'formulaire')),
      value: 1
    });
  }
  if (typeof window.gtag === 'function') {
    envoyer();
  } else {
    document.addEventListener('vg:analytics-pret', envoyer, { once: true });
  }
})();
</script>
@endpush

@section('contenu')
<section class="section">
  <div class="cta">
    <h2>Merci, c’est bien reçu&nbsp;!</h2>
    <p>
      Nous revenons vers vous <strong>sous 24h ouvrables</strong> avec un devis gratuit.
      Une urgence&nbsp;? Appelez-nous directement.
    </p>
    @if(!empty($reglages['telephone_public']))
      <a href="tel:{{ preg_replace('/\s+/', '', $reglages['telephone_public']) }}" class="btn">
        {{ $reglages['telephone_public'] }}
      </a>
    @endif
  </div>

  <div class="tete" style="margin-top:44px">
    <h2>En attendant</h2>
    <p>Découvrez nos réalisations avant/après à Bruxelles.</p>
    <a href="{{ route('realisations.index') }}" class="btn btn-cobalt" style="margin-top:12px">Voir les réalisations</a>
  </div>
</section>
@endsection
