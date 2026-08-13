<x-mail::message>
# Nouvelle demande de devis

**{{ $devis->nom }}** — {{ $devis->telephone }}
@if($devis->email)
{{ $devis->email }}
@endif

<x-mail::table>
| Champ | Valeur |
|:------|:-------|
| Prestation | {{ $devis->prestation ?: '—' }} |
| Commune | {{ $devis->commune ?: '—' }} |
| Volume estimé | {{ $devis->volume_estime ?: '—' }} |
| Page d'origine | {{ $devis->source ?: '—' }} |
| Reçue le | {{ $devis->cree_le->format('d/m/Y à H:i') }} |
</x-mail::table>

@if($devis->message)
**Message du client**

{{ $devis->message }}
@endif

<x-mail::button :url="'tel:'.preg_replace('/\s+/', '', $devis->telephone)">
Rappeler {{ $devis->nom }}
</x-mail::button>

Demande n°{{ $devis->id }}
</x-mail::message>
