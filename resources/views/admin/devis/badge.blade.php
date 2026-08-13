@php
  $libelles = [
      'nouveau' => 'Nouveau',
      'contacte' => 'Contacté',
      'devis_envoye' => 'Devis envoyé',
      'gagne' => 'Gagné',
      'perdu' => 'Perdu',
  ];
  $classes = [
      'nouveau' => 'badge-nouveau',
      'contacte' => 'badge-contacte',
      'devis_envoye' => 'badge-contacte',
      'gagne' => 'badge-gagne',
      'perdu' => 'badge-perdu',
  ];
@endphp
<span class="badge {{ $classes[$statut] ?? '' }}">{{ $libelles[$statut] ?? $statut }}</span>
