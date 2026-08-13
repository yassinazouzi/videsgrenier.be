@php
  $e = config('site.entreprise');
  $donnees = [
      '@context' => 'https://schema.org',
      '@type' => 'LocalBusiness',
      '@id' => url('/').'#entreprise',
      'name' => $e['nom'],
      'url' => url('/'),
      'description' => 'Service de débarras, vide-maison et vide-appartement dans les 19 communes de Bruxelles, avec rachat des objets de valeur déduit du prix.',
      'telephone' => $reglages['telephone_public'] ?? null,
      'email' => $reglages['email_devis'] ?? null,
      'priceRange' => $e['prix_indicatif'],
      'openingHours' => $e['horaires'],
      'address' => [
          '@type' => 'PostalAddress',
          'streetAddress' => $e['rue'],
          'postalCode' => $e['code_postal'],
          'addressLocality' => $e['ville'],
          'addressCountry' => $e['pays'],
      ],
      'geo' => [
          '@type' => 'GeoCoordinates',
          'latitude' => $e['latitude'],
          'longitude' => $e['longitude'],
      ],
      'areaServed' => collect($communes ?? [])->map(fn ($c) => [
          '@type' => 'City',
          'name' => $c->nom,
      ])->all(),
  ];

  // Réseaux sociaux : configurés depuis /admin/reglages, complétés par config/site.php
  // pour les liens qui n'ont pas leur propre champ (ex. fiche Google Business Profile).
  $reseaux = array_filter([
      $reglages['facebook_url'] ?? null,
      $reglages['instagram_url'] ?? null,
      $reglages['tiktok_url'] ?? null,
      ...($e['same_as'] ?? []),
  ]);

  if (! empty($reseaux)) {
      $donnees['sameAs'] = array_values($reseaux);
  }
@endphp
<script type="application/ld+json">
{!! json_encode(array_filter($donnees), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
