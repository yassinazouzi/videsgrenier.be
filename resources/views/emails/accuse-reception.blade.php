<x-mail::message>
# Merci {{ $devis->nom }}, votre demande est bien reçue

Nous revenons vers vous **sous 24h ouvrables** avec un devis gratuit et sans engagement.

<x-mail::table>
| Votre demande | |
|:------|:-------|
| Prestation | {{ $devis->prestation ?: 'Débarras' }} |
| Commune | {{ $devis->commune ?: 'Bruxelles' }} |
| Téléphone | {{ $devis->telephone }} |
</x-mail::table>

Pour rappel : **les objets de valeur que nous rachetons sont déduits du prix** de la prestation.

Besoin d'une réponse plus rapide ? Écrivez-nous sur WhatsApp.

Cordialement,
L'équipe Videsgrenier.be
</x-mail::message>
