<?php

return [

    // Cohérence NAP (Nom-Adresse-Téléphone) : ces valeurs doivent être identiques
    // partout, y compris sur la fiche Google Business Profile.
    'entreprise' => [
        'nom' => 'Videsgrenier.be',
        'rue' => 'Rue de la Loi 1',
        'code_postal' => '1000',
        'ville' => 'Bruxelles',
        'pays' => 'BE',
        'latitude' => 50.8467,
        'longitude' => 4.3525,
        'horaires' => 'Mo-Sa 09:00-18:00',
        'prix_indicatif' => '€€',
        'same_as' => [
            // 'https://www.google.com/maps/place/...',
            // 'https://www.facebook.com/...',
        ],
    ],

    'faq' => [
        [
            'q' => 'Combien coûte un débarras à Bruxelles ?',
            'r' => 'Le prix dépend du volume, de l’étage et de l’accès. Comptez généralement de 300 € pour une cave à 1 500 € pour une maison complète. Les objets de valeur que nous rachetons sont déduits du devis, ce qui réduit souvent la facture de 20 à 50 %.',
        ],
        [
            'q' => 'Sous quel délai intervenez-vous ?',
            'r' => 'Le devis part sous 24h ouvrables. L’intervention a généralement lieu sous 24 à 48h après votre accord, et le jour même en cas d’urgence selon nos disponibilités.',
        ],
        [
            'q' => 'Dans quelles communes intervenez-vous ?',
            'r' => 'Dans les 19 communes de la Région de Bruxelles-Capitale : Anderlecht, Auderghem, Berchem-Sainte-Agathe, Bruxelles-Ville, Etterbeek, Evere, Forest, Ganshoren, Ixelles, Jette, Koekelberg, Molenbeek-Saint-Jean, Saint-Gilles, Saint-Josse-ten-Noode, Schaerbeek, Uccle, Watermael-Boitsfort, Woluwe-Saint-Lambert et Woluwe-Saint-Pierre.',
        ],
        [
            'q' => 'Rachetez-vous les meubles et objets ?',
            'r' => 'Oui. Nous rachetons meubles anciens, électroménager en état, vaisselle, livres, vinyles, bibelots et objets de brocante. L’estimation est faite lors de la visite et le montant est directement déduit du prix du débarras.',
        ],
        [
            'q' => 'Le logement est-il nettoyé après le débarras ?',
            'r' => 'Oui, le logement est rendu vide, balayé et propre. Un nettoyage approfondi (avant remise des clés ou état des lieux de sortie) peut être ajouté au devis.',
        ],
        [
            'q' => 'Que devient ce qui n’est pas racheté ?',
            'r' => 'Tout est trié : don aux associations bruxelloises, revente en brocante, et évacuation du reste en déchetterie agréée avec tri des encombrants. Environ 80 % du volume est revalorisé.',
        ],
    ],
];
