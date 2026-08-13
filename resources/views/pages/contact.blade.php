@extends('layouts.site')

@section('titre', 'Contact — Videsgrenier.be, débarras à Bruxelles')
@section('description', 'Contactez-nous pour un débarras à Bruxelles : téléphone, e-mail ou WhatsApp. Devis gratuit sous 24h.')

@section('contenu')
<section class="section">
  <div class="tete">
    <span class="eyebrow">Contact</span>
    <h2>Parlons de votre débarras</h2>
    <p>Par téléphone, par e-mail ou via WhatsApp — comme vous préférez.</p>
  </div>

  <div class="zone">
    <div>
      <h2>Nos coordonnées</h2>
      <ul style="margin-top:18px;display:grid;gap:12px">
        @if(!empty($reglages['telephone_public']))
          <li><strong>Téléphone</strong><br>
            <a class="mono" href="tel:{{ preg_replace('/\s+/', '', $reglages['telephone_public']) }}">{{ $reglages['telephone_public'] }}</a>
          </li>
        @endif
        @if(!empty($reglages['email_devis']))
          <li><strong>E-mail</strong><br>
            <a class="mono" href="mailto:{{ $reglages['email_devis'] }}">{{ $reglages['email_devis'] }}</a>
          </li>
        @endif
        @if(!empty($reglages['whatsapp_horaires']))
          <li><strong>Horaires</strong><br><span class="mono">{{ $reglages['whatsapp_horaires'] }}</span></li>
        @endif
        <li><strong>Zone</strong><br><span class="muet">Les 19 communes de Bruxelles-Capitale</span></li>
      </ul>
    </div>

    <div class="devis-carte">
      <h2>Ou écrivez-nous</h2>
      <p>Réponse sous 24h ouvrables.</p>
      @include('partials.form-devis')
    </div>
  </div>
</section>
@endsection
