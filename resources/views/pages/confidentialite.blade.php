@extends('layouts.site')

@section('titre', 'Politique de confidentialité — Videsgrenier.be')
@section('noindex', true)

@section('contenu')
<section class="section">
  <div class="tete"><h2>Politique de confidentialité</h2></div>

  <div class="faq" style="margin:0 auto">
    <h3>Données collectées</h3>
    <p class="muet">
      Via le formulaire de devis : nom, téléphone, e-mail (facultatif), commune,
      type de prestation, volume estimé et message. Ces données servent uniquement
      à établir votre devis et à vous recontacter.
    </p>

    <h3 style="margin-top:26px">Base légale et durée de conservation</h3>
    <p class="muet">
      Le traitement repose sur votre demande (mesures précontractuelles). Les demandes
      de devis sont conservées 3 ans après le dernier contact, puis supprimées.
    </p>

    <h3 style="margin-top:26px">Destinataires</h3>
    <p class="muet">
      Vos données ne sont ni vendues ni transmises à des tiers à des fins commerciales.
      Elles sont accessibles à notre équipe et à notre hébergeur (OVH, France).
    </p>

    <h3 style="margin-top:26px">Vos droits</h3>
    <p class="muet">
      Vous disposez d’un droit d’accès, de rectification, d’effacement, de limitation et
      d’opposition. Écrivez à
      @if(!empty($reglages['email_devis']))
        <a href="mailto:{{ $reglages['email_devis'] }}">{{ $reglages['email_devis'] }}</a>.
      @else
        notre adresse de contact.
      @endif
      Vous pouvez également introduire une réclamation auprès de l’Autorité de protection
      des données (Belgique).
    </p>

    <h3 style="margin-top:26px">Cookies</h3>
    <p class="muet">
      Le site dépose un cookie de session nécessaire à son fonctionnement. Les cookies
      de mesure d’audience ne sont déposés qu’après votre consentement.
    </p>
  </div>
</section>
@endsection
