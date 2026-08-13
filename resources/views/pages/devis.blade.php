@extends('layouts.site')

@section('titre', 'Demander un devis de débarras à Bruxelles — gratuit sous 24h')
@section('description', 'Formulaire de devis gratuit pour un débarras, vide-maison ou vide-appartement à Bruxelles. Réponse sous 24h, rachat déduit du prix.')

@section('contenu')
<section class="section">
  <div class="tete">
    <span class="eyebrow">Devis gratuit</span>
    <h2>Décrivez votre débarras</h2>
    <p>Réponse sous 24h ouvrables, prix ferme, sans engagement.</p>
  </div>

  <div class="devis-carte" style="max-width:640px;margin:0 auto">
    <h2>Votre demande</h2>
    <p>Les champs marqués * sont obligatoires.</p>
    @include('partials.form-devis')
  </div>
</section>
@endsection
