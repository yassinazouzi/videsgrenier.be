# Audit Schema.org / JSON-LD — Videsgrenier.be

Date de l'audit : 2026-08-13
Environnement testé : `http://localhost/videsgrenier/public/` (site non déployé publiquement)
Méthode : lecture des sources Blade + extraction et validation JSON stricte du HTML réellement rendu (`curl`) pour 4 pages représentatives :
- `/` (accueil) → LocalBusiness + FAQPage
- `/debarras/ixelles` (commune) → Service
- `/services/vide-maison` (service) → Service
- `/blog/prix-debarras-bruxelles` (article) → BlogPosting

Résultat de la validation JSON stricte (parseur natif) : **les 5 blocs JSON-LD rencontrés sont syntaxiquement valides** (pas de virgule finale, guillemets corrects). Aucun problème de parsing détecté. Les problèmes ci-dessous portent sur le contenu, la conformité aux types Schema.org / Google, et la cohérence inter-pages.

---

## Critical

### 1. Adresse fictive publiée dans le LocalBusiness JSON-LD (`config/site.php`)
`config/site.entreprise.rue` contient un placeholder non remplacé : `'Rue de la Loi 1'`. Cette valeur est injectée telle quelle dans `streetAddress` du schema `LocalBusiness` diffusé sur la page d'accueil (confirmé dans le HTML rendu) :

```json
"address": {
  "@type": "PostalAddress",
  "streetAddress": "Rue de la Loi 1",
  "postalCode": "1000",
  "addressLocality": "Bruxelles",
  "addressCountry": "BE"
}
```

C'est un problème déjà identifié ailleurs (NAP), mais il doit apparaître ici car il **pollue directement le JSON-LD publié** : Google (et toute IA consommant le JSON-LD) indexera une fausse adresse comme donnée structurée officielle de l'entreprise. Rue de la Loi 1 est en réalité le siège du Parlement européen — un mismatch NAP entre le JSON-LD, la fiche Google Business Profile (à venir) et le pied de page serait un signal négatif fort pour le SEO local, et un problème de fiabilité pour l'AEO/GEO (une IA pourrait citer cette fausse adresse).
**Action :** ne pas publier ce schema (ou masquer `address`) tant que `config/site.php` n'a pas la vraie adresse. Fichier : `C:\wamp64\www\videsgrenier\config\site.php` (ligne 9), consommé par `C:\wamp64\www\videsgrenier\resources\views\partials\jsonld-localbusiness.blade.php` (ligne 16).

### 2. `BlogPosting.image` sort à `null` dans le JSON publié (propriété requise par Google pour Article/BlogPosting)
Sur `/blog/prix-debarras-bruxelles`, l'article n'a pas de `image_une` renseignée. Le template ne filtre pas les valeurs nulles (contrairement à `jsonld-localbusiness.blade.php` qui utilise `array_filter()`), donc le JSON publié contient littéralement :

```json
{"@context":"https://schema.org","@type":"BlogPosting",
 "headline":"Prix d'un débarras à Bruxelles : combien ça coûte réellement en 2026",
 "description":"...",
 "datePublished":"2026-08-10T22:32:16+00:00",
 "image":null,
 "author":{"@type":"Organization","name":"Videsgrenier.be"},
 "publisher":{"@type":"Organization","name":"Videsgrenier.be"},
 "mainEntityOfPage":"http://localhost/videsgrenier/public/blog/prix-debarras-bruxelles"}
```

`image` est une propriété **requise** par Google pour le rich result Article (le Rich Results Test rejettera ce bloc pour cet article précis). C'est un cas réel et pas théorique : cet article n'a simplement pas d'image de une. Tout article publié sans `image_une` cassera le rich result.
**Action :**
- Rendre `image_une` obligatoire côté back-office pour les articles, ou définir une image par défaut de fallback (logo/illustration générique) dans `article.blade.php`.
- Appliquer `array_filter()` (comme dans `jsonld-localbusiness.blade.php`) pour ne jamais publier de clé JSON-LD à `null`, indépendamment du fix de fond.

Fichier : `C:\wamp64\www\videsgrenier\resources\views\pages\article.blade.php` (lignes 9-19).

---

## High

### 3. Aucun `BreadcrumbList` sur un site à hiérarchie d'URL profonde
Aucune des 4 pages testées ne publie de `BreadcrumbList` (vérifié : un seul bloc `ld+json` par page hors accueil, aucun ne contient `BreadcrumbList`). Le site a pourtant une architecture d'URL clairement hiérarchique et dupliquée à grande échelle :
- `/services/{slug}` (6 services vus dans le menu)
- `/debarras/{commune}` (19 communes)
- `/blog/{slug}` (articles)
- `/realisations/{slug}`

`BreadcrumbList` est un type **officiellement supporté par Google Rich Results** (remplace l'URL affichée par un fil d'Ariane dans les SERP) et aide à clarifier la structure du site pour le crawl/l'indexation, en particulier utile ici vu le volume de pages générées par commune × service.
**Impact estimé :** Élevé car l'opportunité manquée se répète sur ~19 pages commune + N pages service + articles + réalisations, soit potentiellement l'essentiel des pages indexables du site.

**JSON-LD suggéré** (exemple pour `/debarras/ixelles`) :
```json
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Accueil", "item": "https://videsgrenier.be/" },
    { "@type": "ListItem", "position": 2, "name": "Communes", "item": "https://videsgrenier.be/#zone" },
    { "@type": "ListItem", "position": 3, "name": "Débarras Ixelles", "item": "https://videsgrenier.be/debarras/ixelles" }
  ]
}
```
Même logique pour `/services/{slug}` (Accueil > Services > {titre}) et `/blog/{slug}` (Accueil > Blog > {titre}).
**Fichiers concernés :** ajouter un partial réutilisable (ex. `partials/jsonld-breadcrumb.blade.php`) inclus depuis `commune.blade.php`, `service.blade.php`, `article.blade.php`, `realisations` (non audité ici mais même remarque probable).

### 4. `Service.offers` / prix absents — question posée explicitement dans le brief
Ni `commune.blade.php` ni `service.blade.php` n'ajoutent de `offers` ou de `priceRange` au schema `Service` :

```json
{"@context":"https://schema.org","@type":"Service",
 "name":"Débarras et vide-maison à Ixelles",
 "serviceType":"Débarras, vide-maison, vide-appartement",
 "provider":{"@type":"LocalBusiness","name":"Videsgrenier.be","telephone":"+32 491 64 49 13","url":"http://localhost/videsgrenier/public"},
 "areaServed":{"@type":"City","name":"Ixelles","postalCode":"1050","addressCountry":"BE"},
 "url":"http://localhost/videsgrenier/public/debarras/ixelles"}
```
Note : `priceRange` n'existe pas comme propriété de `Service` dans le vocabulaire Schema.org (c'est une propriété de `LocalBusiness`/`Organization`), donc ce n'est pas literally applicable ici — mais `offers` (type `Offer`, avec `priceSpecification` en fourchette) est la propriété adaptée et manque totalement. Le site affiche pourtant une fourchette de prix concrète dans le contenu FAQ (« de 300 € pour une cave à 1 500 € pour une maison complète »).
**Suggestion :**
```json
"offers": {
  "@type": "Offer",
  "priceCurrency": "EUR",
  "priceSpecification": {
    "@type": "PriceSpecification",
    "minPrice": 300,
    "maxPrice": 1500,
    "priceCurrency": "EUR"
  }
}
```
À noter : `Service` **n'est pas un type à rich result dédié** dans Google Search (contrairement à `Product`/`LocalBusiness`). L'ajout d'`offers` reste utile pour la compréhension d'entité (Knowledge Graph) et pour l'AEO/GEO (une IA peut citer un prix indicatif directement), mais n'ajoutera pas de rich snippet visuel dans Google Search — à clarifier auprès du client pour ne pas sur-vendre l'impact.
**Fichiers :** `C:\wamp64\www\videsgrenier\resources\views\pages\commune.blade.php` (lignes 9-27), `C:\wamp64\www\videsgrenier\resources\views\pages\service.blade.php` (lignes 9-22).

---

## Medium

### 5. `AggregateRating`/`Review` absent malgré la table `temoignages` — opportunité réelle mais à manier avec précaution
La page d'accueil affiche des témoignages clients avec note (`str_repeat('★', $temoignage->note)`), auteur et commune, mais **aucun schema `Review` ou `AggregateRating` n'est généré**, ni dans `jsonld-localbusiness.blade.php` ni ailleurs.

C'est une opportunité manquante en apparence (les étoiles dans les SERP augmentent le CTR), **mais attention à un point de conformité important avant de l'implémenter** : les guidelines Google sur les extraits d'avis (« Review snippet ») interdisent explicitement le balisage de **témoignages auto-publiés par l'entreprise elle-même sur son propre site** (« self-serving reviews »/« testimonials ») — Google les distingue des avis tiers vérifiables (Google Business Profile, Trustpilot, etc.). Marquer les `temoignages` internes en `Review`/`AggregateRating` sur le `LocalBusiness` de la page d'accueil, sans lien vers une source tierce vérifiable, expose à un risque de non-conformité (le rich result ne sera simplement pas affiché, dans le meilleur des cas ; dans le pire, cela peut être considéré comme du balisage trompeur).
**Recommandation :**
- Ne pas ajouter `Review`/`AggregateRating` directement sourcé sur la table `temoignages` interne en l'état.
- Si le site obtient de vrais avis Google Business Profile (une fois l'établissement créé/vérifié — cf. finding Critical #1 sur l'adresse), envisager soit un lien `sameAs` vers la fiche Google (déjà prévu techniquement, cf. #7 ci-dessous) soit une intégration d'avis tiers vérifiés avec attribution correcte.
- Alternative sans risque : garder les témoignages en HTML pur (comme actuellement) sans schema dédié — c'est d'ailleurs ce qui est fait aujourd'hui, ce qui est en réalité la position la plus sûre.

### 6. `LocalBusiness` sans `image` (propriété recommandée par Google)
Le schema `LocalBusiness` de la page d'accueil n'inclut aucune propriété `image`, alors que Google la recommande pour ce type (photo de l'établissement/logo). Le site a par ailleurs un logo (`Videsgrenier<span>.be</span>` en CSS, pas d'image dédiée détectée) et des hero-photos disponibles (`heroPhotos`/`heroVideo`). **Action suggérée :** ajouter une URL absolue d'image représentative (logo ou photo de véhicule/équipe) via `config/site.php` ou `reglages`.
Fichier : `C:\wamp64\www\videsgrenier\resources\views\partials\jsonld-localbusiness.blade.php`.

### 7. Entité `LocalBusiness` non référencée (`@id`) hors de la page d'accueil — incohérence de graphe
Le partial `jsonld-localbusiness.blade.php` (qui définit `@id: url('/').'#entreprise'`) n'est inclus **que sur `accueil.blade.php`** (vérifié par recherche dans tout `resources/views` : un seul `@include`). Les pages `commune.blade.php` et `service.blade.php` créent chacune un **nouvel objet `LocalBusiness` anonyme** en tant que `provider` du `Service`, sans `@id`, sans adresse ni geo :
```json
"provider": {
  "@type": "LocalBusiness",
  "name": "Videsgrenier.be",
  "telephone": "+32 491 64 49 13",
  "url": "http://localhost/videsgrenier/public"
}
```
Google/les moteurs ne peuvent pas relier explicitement ce `provider` à l'entité canonique publiée sur l'accueil (même nom, mais pas de lien fort). Idem côté `BlogPosting` : `publisher`/`author` sont des `Organization` sans `@id`, sans `logo` (recommandé pour l'affichage AMP/Article rich result).
**Recommandation :** remplacer les objets `LocalBusiness`/`Organization` dupliqués par une simple référence `"@id": "http://localhost/videsgrenier/public#entreprise"` (ou l'URL absolue en prod), pour construire un vrai graphe d'entités cohérent. Ajouter `logo` (ImageObject) à l'`Organization` publisher du `BlogPosting`.
**Cohérence par ailleurs positive :** le nom `"Videsgrenier.be"` est identique sur les 4 templates testés (LocalBusiness, provider de Service ×2, author/publisher de BlogPosting) — pas de divergence de nom détectée.

### 8. FAQPage sur page commerciale — déjà signalé, à garder en visibilité
`accueil.blade.php` publie un `FAQPage` complet et valide (6 questions/réponses, structure conforme). Comme Videsgrenier.be est un site commercial (pas gouvernemental/santé), ce schema **n'est plus éligible aux rich results Google FAQ depuis août 2023**. Le bloc reste valide et sans danger technique, et garde un intérêt réel pour la citation par les moteurs IA/LLM (AEO/GEO — les réponses structurées Q/R sont un format que les IA génératives exploitent bien).
**Classé Medium et non Critical/High** : ce n'est pas un problème à corriger en urgence, juste une attente de rich result Google à ne plus avoir sur ce schema. Aucune action requise sauf alignement des attentes avec le client (« ne vous attendez pas à des étoiles/accordéons FAQ dans Google pour ce site »).

---

## Low / Info

### 9. `sameAs` dynamique — implémentation correcte, vérifiée
Le bloc PHP de `jsonld-localbusiness.blade.php` (lignes 32-43) construit `sameAs` dynamiquement à partir de `reglages.facebook_url`, `reglages.instagram_url`, `reglages.tiktok_url`, complétés par `config('site.entreprise.same_as')` :
```php
$reseaux = array_filter([
    $reglages['facebook_url'] ?? null,
    $reglages['instagram_url'] ?? null,
    $reglages['tiktok_url'] ?? null,
    ...($e['same_as'] ?? []),
]);

if (! empty($reseaux)) {
    $donnees['sameAs'] = array_values($reseaux);
}
```
C'est **correctement implémenté** :
- `array_filter()` retire bien les valeurs vides/null.
- `array_values()` re-séquence les clés après filtrage — bon réflexe qui évite que `json_encode()` transforme le tableau en objet JSON (`{}`) si les clés ne sont plus consécutives après filtrage.
- La clé `sameAs` n'est ajoutée au tableau final que si non vide, ce qui est confirmé dans le HTML rendu de l'accueil : `sameAs` est absent du JSON publié tant que `facebook_url`/`instagram_url`/`tiktok_url` sont vides (comportement actuel en base) — pas de `"sameAs":[]` ou de `"sameAs":null` qui traînerait.

Aucune action requise ici ; à re-tester une fois les champs réseaux sociaux/reglages renseignés en admin, mais le code est prêt.

### 10. `openingHours` en format texte simple
`"openingHours": "Mo-Sa 09:00-18:00"` est un format valide accepté par Schema.org, mais Google recommande la forme structurée `openingHoursSpecification` (tableau d'objets `OpeningHoursSpecification` avec `dayOfWeek`/`opens`/`closes`) pour une meilleure fiabilité de parsing, notamment si les horaires se complexifient un jour (jours fériés, horaires différents le samedi, etc.). Amélioration facultative, pas bloquante tant qu'il n'y a qu'une seule plage continue.

### 11. `dateModified` absent du `BlogPosting`
Seul `datePublished` est présent. Google recommande aussi `dateModified` pour les signaux de fraîcheur de contenu (Article/BlogPosting). À ajouter si le modèle `Article`/`article` dispose d'un timestamp de mise à jour (`updated_at` Eloquent standard, probablement déjà disponible).

### 12. URLs `localhost` dans tout le JSON-LD — rappel pré-lancement
Toutes les valeurs `url`/`@id`/`mainEntityOfPage` observées pointent vers `http://localhost/videsgrenier/public` (piloté par `url()->current()` / `url('/')`, donc par `APP_URL`). C'est normal en environnement local et se corrigera automatiquement au déploiement si `APP_URL` est bien positionné sur `https://videsgrenier.be` en production — mais c'est un point de check-list à ne pas oublier avant mise en ligne (le JSON-LD ne doit jamais publier de `localhost` en prod). Aucune action de code requise, juste une vérification de configuration `.env` à la mise en production.

### 13. Pas de `WebSite`/`Organization` schema global avec `SearchAction`
Aucun schema `WebSite` (avec `potentialAction: SearchAction`) n'est présent. Aucune route `/recherche` ou équivalent n'a été trouvée dans `routes/web.php`, donc le `SearchAction` (sitelinks searchbox) n'est pas applicable en l'état — pas une opportunité pertinente tant qu'il n'y a pas de fonction recherche sur le site. Mentionné pour mémoire (Low), pas une recommandation active.

---

## Résumé priorisé

| # | Finding | Sévérité |
|---|---|---|
| 1 | Adresse fictive `Rue de la Loi 1` publiée dans LocalBusiness | **Critical** |
| 2 | `BlogPosting.image` = `null` (requis par Google) sur article sans image | **Critical** |
| 3 | `BreadcrumbList` absent sur toute l'arborescence /services, /debarras, /blog | **High** |
| 4 | `Service.offers` absent malgré une fourchette de prix connue | **High** |
| 5 | `Review`/`AggregateRating` absent — opportunité à manier avec prudence (self-serving reviews) | Medium |
| 6 | `LocalBusiness.image` absent | Medium |
| 7 | Pas de `@id` partagé entre LocalBusiness accueil et provider/publisher des autres pages | Medium |
| 8 | `FAQPage` sur site commercial — plus de rich result Google, encore utile pour IA/GEO | Medium |
| 9 | `sameAs` dynamique — implémentation correcte, vérifiée | Info (positif) |
| 10 | `openingHours` en texte simple plutôt que structuré | Low |
| 11 | `dateModified` absent du BlogPosting | Low |
| 12 | URLs `localhost` dans le JSON-LD — check-list pré-lancement | Low/Info |
| 13 | Pas de `WebSite`/`SearchAction` — non applicable (pas de recherche sur le site) | Low/Info |

---

## Fichiers audités
- `C:\wamp64\www\videsgrenier\resources\views\partials\jsonld-localbusiness.blade.php`
- `C:\wamp64\www\videsgrenier\resources\views\pages\accueil.blade.php`
- `C:\wamp64\www\videsgrenier\resources\views\pages\commune.blade.php`
- `C:\wamp64\www\videsgrenier\resources\views\pages\service.blade.php`
- `C:\wamp64\www\videsgrenier\resources\views\pages\article.blade.php`
- `C:\wamp64\www\videsgrenier\resources\views\layouts\site.blade.php`
- `C:\wamp64\www\videsgrenier\config\site.php`
