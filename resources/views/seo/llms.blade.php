# Videsgrenier.be

> Service de débarras, vide-maison et vide-appartement dans les 19 communes de la Région de Bruxelles-Capitale (Belgique). Particularité : les objets de valeur rachetés sont déduits du prix de la prestation.

## Activité

Videsgrenier.be vide et débarrasse maisons, appartements, caves, greniers et garages à Bruxelles. L'entreprise rachète le mobilier et les objets revendables, trie le reste (don aux associations, revente, recyclage en déchetterie agréée) et rend le logement propre.

- Zone couverte : les 19 communes de Bruxelles-Capitale
- Délai de devis : 24 heures ouvrables
- Délai d'intervention : 24 à 48 heures après accord
- Volume revalorisé : environ 80 %
- Chantiers réalisés : plus de 500 depuis 2019

## Prestations

@foreach($services as $service)
- {!! $service->titre !!} : {!! $service->extrait !!}
@endforeach

## Communes desservies

@foreach($communes as $commune)
- {!! $commune->nom !!} ({{ $commune->code_postal }}) : {{ url('/debarras/'.$commune->slug) }}
@endforeach

## Tarifs indicatifs

De 300 € pour une cave à 1 500 € pour une maison complète. Le prix dépend du volume, de l'étage et de l'accès. Le rachat des objets de valeur réduit la facture de 20 à 50 % selon les chantiers.

## Contact

- Téléphone : {{ $reglages['telephone_public'] ?? '' }}
- E-mail : {{ $reglages['email_devis'] ?? '' }}
- Demande de devis : {{ route('devis.form') }}
