# Audit SEO Local — Videsgrenier.be

**Date de l'audit :** 2026-08-13
**Site audité :** http://localhost/videsgrenier/public/ (environnement local, non déployé publiquement)
**Type d'activité détecté :** Service à domicile (SAB — Service Area Business), sans commerce physique visité par la clientèle
**Secteur détecté :** Home Services — débarras / vide-maison / vide-appartement (proche de la catégorie "déménagement / gestion des encombrants")
**Zone de chalandise :** 19 communes de la Région de Bruxelles-Capitale

---

## 1. Score SEO Local : 46 / 100

| Dimension | Poids | Score obtenu | Commentaire synthétique |
|---|---|---|---|
| Signaux GBP | 25 % | 4 / 25 | Fiche Google Business Profile inexistante — c'est le plus gros manque, structurel |
| Avis & réputation | 20 % | 5 / 20 | Fonctionnalité témoignages en place mais 0 avis publié actuellement, aucun schema Review/AggregateRating |
| SEO on-page local | 20 % | 17 / 20 | Très bon point fort : 19 pages communes avec contenu réellement unique et localisé |
| Cohérence NAP & citations | 15 % | 4 / 15 | Téléphone cohérent partout, mais adresse placeholder fictive + 0 citation externe |
| Schema local | 10 % | 6 / 10 | LocalBusiness + Service par commune présents, mais type générique, adresse fictive, pas de Review schema |
| Liens & autorité locale | 10 % | 0 / 10 | Aucun backlink local, aucune citation — normal en pré-lancement mais compte à 0 |

Le score est délibérément bas parce que le site n'est pas encore lancé : c'est un score de **pré-lancement**, pas un jugement sur la qualité du travail effectué côté code (qui est globalement solide sur la partie on-page). Une fois le plan d'action ci-dessous exécuté, ce score peut monter rapidement.

---

## 2. Type d'activité et cohérence avec le modèle SAB

Le site ne montre l'adresse nulle part dans le HTML visible (header, footer, page contact, page à-propos) — j'ai vérifié ces 4 emplacements par curl, aucun ne mentionne "Rue de la Loi" ni "1000 Bruxelles" en visible. Seul le JSON-LD contient une adresse postale complète. C'est cohérent avec les bonnes pratiques Google pour une SAB (ne pas afficher/publier une adresse que les clients ne visitent pas), **mais** cela crée une incohérence de fond : le schema utilise `@type: LocalBusiness` avec une `PostalAddress` complète, ce qui signale à Google "j'ai un lieu physique visité par des clients" — alors que le modèle réel est 100 % service à domicile. Il faut trancher ce point avant le lancement (voir §7, action Critical).

`areaServed` est bien structuré : un tableau de 19 objets `@type: City` avec noms exacts des communes, plutôt qu'un rayon flou en km — c'est la bonne pratique pour une SAB multi-communes. Sur les pages `/debarras/{commune}`, le schema `Service` répète un `areaServed` unique et précis (`City` + `postalCode` + `addressCountry`) par commune, ce qui est également correct.

---

## 3. Audit NAP (Nom / Adresse / Téléphone) — tableau de cohérence

| Source | Nom | Adresse | Téléphone |
|---|---|---|---|
| `config/site.php` | Videsgrenier.be | Rue de la Loi 1, 1000 Bruxelles, BE (⚠ placeholder fictif) | — (géré séparément via reglages) |
| JSON-LD LocalBusiness (accueil) | Videsgrenier.be | Rue de la Loi 1 / 1000 / Bruxelles / BE | +32 491 64 49 13 |
| JSON-LD Service (pages commune) | Videsgrenier.be (provider) | absente (seul `areaServed` = commune) | +32 491 64 49 13 |
| Header (visible) | Videsgrenier.be | absente | +32 491 64 49 13 |
| Footer (visible) | Videsgrenier.be | absente | +32 491 64 49 13 |
| Page /contact (visible) | — | absente | +32 491 64 49 13 |
| Page /a-propos (visible) | — | absente | +32 491 64 49 13 |

**Constats :**
- Le **téléphone est parfaitement cohérent** partout où il apparaît (+32 491 64 49 13, format identique, lien `tel:` normalisé sans espaces). Aucun problème ici.
- L'**adresse n'existe que dans le JSON-LD** — nulle part en HTML visible. Ce n'est pas une incohérence technique en soi (choix voulu pour une SAB), mais combiné au fait que l'adresse est un **placeholder fictif** ("Rue de la Loi 1" est l'adresse du Parlement fédéral belge, une adresse institutionnelle bien connue à Bruxelles), c'est un problème CRITICAL : si ce JSON-LD est déployé en production tel quel, Google indexera une fausse adresse structurée pour l'entreprise, et le jour où la fiche GBP sera créée avec la vraie adresse (ou "sans adresse visible" en mode SAB), l'incohérence NAP entre le schema du site et GBP sera un signal négatif classique de confusion NAP — cela peut retarder ou empêcher la validation de la fiche GBP (Google vérifie la cohérence adresse site ↔ fiche lors de la validation), et nuire au ranking local une fois la fiche active.
- Les coordonnées `geo` (50.8467 / 4.3525) sont également fictives puisqu'elles découlent de la même adresse placeholder, et n'ont que 4 décimales (précision ~11 m) au lieu des 5 décimales recommandées (~1 m) — à corriger en même temps que l'adresse réelle.

---

## 4. Validation du schema LocalBusiness / Service

### Schema principal (accueil) — `resources/views/partials/jsonld-localbusiness.blade.php`

```json
{
  "@type": "LocalBusiness",
  "name": "Videsgrenier.be",
  "url": "...",
  "telephone": "+32 491 64 49 13",
  "email": "devis@videsgrenier.be",
  "priceRange": "€€",
  "openingHours": "Mo-Sa 09:00-18:00",
  "address": { "streetAddress": "Rue de la Loi 1", "postalCode": "1000", "addressLocality": "Bruxelles", "addressCountry": "BE" },
  "geo": { "latitude": 50.8467, "longitude": 4.3525 },
  "areaServed": [ 19 × { "@type": "City", "name": "..." } ]
}
```

| Élément | Statut | Commentaire |
|---|---|---|
| `@type` | ⚠ À corriger | `LocalBusiness` générique. Le vocabulaire schema.org propose `MovingCompany` (sous-type de LocalBusiness), le plus proche du métier débarras/vide-maison. Un type trop générique est identifié par Whitespark 2026 comme lié au facteur #1 négatif ("mauvaise catégorie" côté GBP) — le même principe de précision s'applique au schema du site |
| `name` | OK | Cohérent |
| `address` (requis) | ⚠ Présent mais fictif | Voir §3 — à corriger avant lancement, et à reconsidérer si elle doit même être publiée (SAB) |
| `geo` (recommandé) | ⚠ Présent mais 4 décimales + fictif | Repasser à 5 décimales avec les vraies coordonnées du siège/zone de dépôt une fois connues |
| `openingHoursSpecification` (recommandé) | ❌ Absent | Le site utilise `openingHours` (chaîne simple, valide mais moins riche) au lieu de `openingHoursSpecification` structuré (tableau d'objets `OpeningHoursSpecification` par jour). Recommandé pour un meilleur support des rich results et de la cohérence avec les horaires GBP |
| `telephone` (recommandé) | OK | Présent et cohérent |
| `url` (recommandé) | OK | Présent |
| `areaServed` | OK, bien structuré | Liste de `City`, pertinent pour une SAB |
| `sameAs` | ❌ Absent actuellement | Conditionnel au remplissage de `reglages` et `site.php['same_as']` — normal tant que GBP/réseaux sociaux n'existent pas, mais prévoir de le remplir dès que la fiche GBP existe (lien vers la fiche Maps = signal important) |
| `image` | ❌ Absent | Non présent dans le schema — recommandé d'ajouter au moins une photo (logo/camion/équipe) |
| `aggregateRating` / `review` | ❌ Absent | Voir §5 |
| `@id` | OK | Présent (`#entreprise`), bonne pratique pour lier les entités |

### Schema Service (pages commune) — `resources/views/pages/commune.blade.php`

Vérifié sur `/debarras/ixelles` et `/debarras/uccle` :

```json
{
  "@type": "Service",
  "name": "Débarras et vide-maison à Ixelles",
  "serviceType": "Débarras, vide-maison, vide-appartement",
  "provider": { "@type": "LocalBusiness", "name": "Videsgrenier.be", "telephone": "...", "url": "..." },
  "areaServed": { "@type": "City", "name": "Ixelles", "postalCode": "1050", "addressCountry": "BE" },
  "url": "..."
}
```

- Structure correcte et bien adaptée : `provider` + `areaServed` spécifique à la commune, généré dynamiquement (`$commune->nom`, `$commune->code_postal`) — testé sur 2 communes différentes, les valeurs changent bien à chaque page (pas de duplication de schema).
- Points d'amélioration : `serviceType` est une chaîne unique avec plusieurs valeurs séparées par des virgules plutôt qu'un tableau (`["Débarras", "Vide-maison", "Vide-appartement"]`) ; le `provider` imbriqué duplique une entité `LocalBusiness` sans `@id` de référence vers l'entité principale de l'accueil (préférable d'utiliser `"@id": "http://.../#entreprise"` en référence plutôt que de redéclarer l'objet) ; pas de `BreadcrumbList` associé à ces pages (utile pour renforcer le maillage sémantique commune → accueil).

### FAQPage (accueil)

Schema `FAQPage` bien implémenté avec 6 questions, dont une portant spécifiquement sur la zone de couverture ("Dans quelles communes intervenez-vous ?") — bon signal local complémentaire, à dupliquer/adapter idéalement au niveau de chaque page commune pour renforcer l'angle local (actuellement le FAQ n'est présent que sur l'accueil).

---

## 5. Avis clients et schema Review — constat important

- La fonctionnalité témoignages existe en base (table `temoignages`, modèle `Temoignage`, scope `publies()` filtrant sur `publie = true`), affichée conditionnellement sur l'accueil et sur chaque page commune (filtré par `commune` si présent).
- **Sur l'instance locale testée, 0 témoignage s'affiche actuellement sur l'accueil** (`@if($temoignages->isNotEmpty())` ne se déclenche pas) — soit la base locale ne contient aucun témoignage publié, soit ils existent mais avec `publie = false`. À vérifier côté `/admin/temoignages` avant le lancement : le brief mentionne 3 avis attendus, ils ne sont pas visibles dans l'état actuel de la base locale auditée.
- **Confirmé : aucun schema `Review` ni `AggregateRating`** n'est associé aux témoignages, ni dans le JSON-LD `LocalBusiness` de l'accueil ni sur les pages commune. Les avis affichés en HTML (étoiles `★` générées par `str_repeat`) ne sont donc pas exploitables par Google pour un rich snippet d'étoiles en résultats de recherche.
- Pas de vélocité d'avis mesurable pour l'instant (site non lancé) — mais c'est un point à surveiller dès le lancement : la "règle des 18 jours" (Sterling Sky) signifie qu'une fois la fiche GBP lancée, il faudra un flux régulier de nouveaux avis Google (pas seulement les 3 témoignages du site) pour éviter un décrochage de ranking après les premières semaines.

---

## 6. Qualité des 19 pages locales (`/debarras/{commune}`)

Vérification du contenu de `database/seeders/ContenuCommunesSeeder.php` (4 exemples croisés avec le rendu live d'Ixelles et Uccle) :

- **Contenu réellement unique par commune**, pas de gabarit générique répété. Chaque intro (2 paragraphes, ~150-200 mots) mentionne des **quartiers réels et vérifiables** : Anderlecht (Kuregem, Wayez, Erasme, Scheutbos, Cureghem, Bon-Air), Ixelles (Châtelain, Matonge, Flagey, ULB/VUB, étangs d'Ixelles), Uccle (Fort Jaco, Saint-Job, Vivier d'Oie, Dieweg), Watermael-Boitsfort (Logis-Floréal classé, étang de Boitsfort). C'est un point fort net et rare — le levier SEO local principal du site est solide.
- Chaque page a un **angle différencié cohérent avec la réalité du bâti local** : typologie de logement (villas à Uccle vs kots étudiants à Ixelles vs petits appartements densifiés à Saint-Josse), contraintes d'accès (rues étroites à Cureghem/Saint-Gilles, stationnement à Madou), profil de clientèle (successions à Woluwe-Saint-Pierre, rotations locatives à Etterbeek/Koekelberg, expatriés européens). Ce n'est pas du contenu doorway générique avec le nom de la commune juste substitué — les critères Google contre les pages doorway (test de substitution) sont donc respectés à première vue.
- **Éléments de confiance mentionnés** : devis gratuit sous 24h (répété systématiquement), intervention sous 24-48h, logement rendu "balayé et propre", rachat d'objets déduit du prix, tri via "déchetterie agréée". En revanche, **aucune mention d'assurance (responsabilité civile) ni d'agrément/licence propre à l'entreprise elle-même** n'a été trouvée sur les pages testées (accueil, contact, à-propos) — seule la déchetterie tierce est qualifiée d'"agréée". Pour un service à domicile où des inconnus entrent chez le client, c'est un signal de réassurance manquant et facile à corriger.
- **Maillage interne** : chaque page commune renvoie vers ~20 autres pages commune (bloc "Autres communes" + footer), vers les pages services, et vers les réalisations filtrées par commune quand elles existent — bonne profondeur de maillage, aucune page commune n'est orpheline (confirmé via le sitemap, les 19 slugs y figurent).
- Les 19 URLs `/debarras/{commune}` sont bien présentes dans le sitemap.xml.
- Chaque page a un `<title>` et une meta description uniques, générés depuis le seeder (`meta_title`, `meta_description`), avec code postal inclus — bon signal local supplémentaire.

**Test de substitution doorway (échantillon)** : en comparant les intros d'Anderlecht et de Woluwe-Saint-Pierre, le contenu ne peut pas être interverti sans devenir factuellement faux (l'un parle de maisons ouvrières et successions à Kuregem, l'autre de villas au parc de Woluwe et brocanteurs spécialisés) — test réussi.

---

## 7. Checklist signaux GBP (Google Business Profile)

| Signal | Détecté | Commentaire |
|---|---|---|
| Fiche GBP créée/liée | ❌ | Confirmé : aucune trace (pas de `sameAs` vers Maps, pas d'embed, pas de "g.page" ni "google.com/maps" dans le HTML) — c'est le point déjà identifié comme prioritaire |
| Maps embed sur le site | ❌ | Aucun `<iframe>` Maps détecté sur les pages testées |
| Widget d'avis Google | ❌ | Absent |
| Lien vers posts GBP | ❌ | N/A, pas de fiche |
| Preuve photo (galerie liée GBP) | ⚠ Partiel | Le site a une section `/galeries` et des réalisations avant/après en local, bonne base à réutiliser comme photos GBP une fois la fiche créée, mais aucun lien vers Maps actuellement |
| Catégorie GBP correspondante | N/A | À définir au moment de la création — voir plan d'action |

---

## 8. Citations locales (Tier 1)

Non applicable en profondeur pour un site local non déployé (aucune URL publique à référencer sur des annuaires). Confirmé par la conception du projet : `same_as` dans `config/site.php` est un tableau vide avec des exemples commentés (Maps, Facebook). Aucune citation Yelp/BBB équivalent belge (Pages d'Or, Trustpilot, Indeed local, etc.) n'a de sens à vérifier tant que le domaine public n'existe pas. À traiter entièrement en phase de lancement (§9).

---

## 9. Plan d'action priorisé

### Critical
1. **Créer la fiche Google Business Profile** dès que le site est prêt à être déployé, en catégorie primaire précise (ex. "Junk removal service" / "Débarras" plutôt qu'une catégorie générique type "Service à domicile") — Whitespark 2026 : la catégorie primaire est le facteur #1 de ranking, une mauvaise catégorie est le facteur négatif #1. Choisir le mode "Service Area Business" (adresse masquée) plutôt qu'une adresse physique, pour rester cohérent avec le modèle réel de l'entreprise.
2. **Remplacer l'adresse placeholder fictive** ("Rue de la Loi 1, 1000 Bruxelles") dans `config/site.php` — soit par la vraie adresse du siège si elle sera masquée en mode SAB sur GBP (cohérence légale/NAP même si non affichée publiquement), soit retirer purement le champ `address` structuré du JSON-LD si le modèle SAB choisi ne prévoit aucune adresse déclarée. Mettre à jour `latitude`/`longitude` avec les vraies coordonnées à 5 décimales en même temps. **Impact si non corrigé** : incohérence NAP entre schema du site et future fiche GBP, ce qui peut bloquer la validation GBP ou générer un signal de confusion NAP pénalisant le ranking local dès le lancement.
3. **Décider explicitement du modèle de schema pour une SAB** : soit conserver `address` (adresse légale non visitée, non affichée en HTML — pratique courante et acceptée), soit la retirer entièrement du JSON-LD si l'entreprise ne souhaite communiquer aucune adresse, même structurée. Documenter ce choix pour éviter une régression future.

### High
4. **Ajouter un schema `Review`/`AggregateRating`** lié aux témoignages existants (au minimum sur l'accueil, idéalement aussi sur les pages commune concernées), une fois qu'un volume d'avis suffisant et légitime existe (attention : ne pas fabriquer de faux avis, cela viole les règles Google et peut faire l'objet de sanctions).
5. **Vérifier/publier les témoignages en base** — au moment de l'audit, 0 témoignage n'apparaît sur l'accueil local malgré la fonctionnalité en place ; confirmer que 3+ avis légitimes sont bien marqués `publie = true` avant le lancement.
6. **Changer le `@type` du schema principal** de `LocalBusiness` vers `MovingCompany` (sous-type le plus proche disponible dans schema.org pour débarras/vide-maison), pour une catégorisation plus précise cohérente avec la catégorie GBP choisie.
7. **Planifier les citations locales belges** dès le lancement public : Google Business Profile en premier, puis Pages d'Or / Pages Blanches Belgique, Trustpilot, un profil Facebook/Instagram professionnel actif (le code prévoit déjà ces champs dans `/admin/reglages`), et des annuaires sectoriels belges spécialisés déménagement/débarras. Objectif : cohérence NAP stricte sur chacune (mêmes nom, téléphone, format d'adresse — ou absence cohérente d'adresse si SAB).
8. **Ajouter des mentions de confiance propres à l'entreprise** (assurance responsabilité civile, numéro d'entreprise BCE/TVA visible en pied de page ou page à-propos, éventuel agrément régional pour le transport de déchets) — actuellement seule la déchetterie tierce est qualifiée d'"agréée", rien ne rassure sur l'entreprise elle-même. Important pour un service où des inconnus entrent au domicile du client.

### Medium
9. **Ajouter `openingHoursSpecification` structuré** (au lieu de la simple chaîne `openingHours`) et une propriété `image` dans le schema LocalBusiness pour améliorer la richesse du balisage.
10. **Dupliquer/adapter une mini-FAQ locale par commune** (au moins 2-3 questions spécifiques, ex. délai d'intervention dans cette commune, contraintes d'accès) avec un schema `FAQPage` par page commune, pour renforcer davantage l'angle local et la visibilité AEO déjà amorcée sur l'accueil.
11. **Référencer l'entité `LocalBusiness` par `@id`** dans le `provider` du schema `Service` des pages commune plutôt que de la redéclarer, pour une meilleure consolidation d'entité côté Google.
12. **Ajouter un schema `BreadcrumbList`** sur les pages commune (Accueil > Débarras > {Commune}) pour renforcer le maillage sémantique.

### Low
13. `serviceType` en tableau plutôt qu'en chaîne à virgules dans le schema `Service`.
14. Remplir `sameAs` (site + JSON-LD) dès que la fiche GBP et les réseaux sociaux existent — les champs sont déjà prévus dans le code (`config/site.php` et `/admin/reglages`), il ne manque que le contenu.
15. Une fois GBP actif, planifier une cadence régulière d'avis (règle des 18 jours) pour éviter un décrochage de ranking après les premières semaines de vie de la fiche.

---

## 10. Limites de cet audit

- **Site non déployé publiquement** : impossible de vérifier réellement l'indexation Google, le rendu du local pack, ou la présence effective sur des annuaires (Yelp n'existe pas en Belgique pertinemment ; testé conceptuellement, pas vérifiable par `site:` search sur un domaine `localhost`).
- **Fiche GBP inexistante** : tout ce qui dépend de données GBP (catégorie effective, photos, posts, Q&A, note moyenne réelle) n'a pas pu être évalué et ne pourra l'être qu'après création de la fiche.
- **Pas d'accès à des outils payants** (DataForSEO, Whitespark, BrightLocal) dans cet environnement — aucun outil MCP DataForSEO n'était disponible lors de cet audit, donc aucune donnée live de local pack, de position ou de citations concurrentes n'a pu être récupérée.
- **Proximité géographique** (55,2 % de la variance de ranking selon l'étude Search Atlas) est hors du contrôle du site/code et n'est pas évaluable avant le lancement effectif avec une vraie adresse/zone de service.
- **État de la base de données locale** : le constat "0 témoignage publié" reflète l'état de la base au moment du test (`http://localhost/videsgrenier/public/`) et peut différer de l'état réel en environnement de développement si des données ont été ajoutées après cet audit.
- Audit réalisé en lecture seule via `curl` et lecture de fichiers — aucune modification n'a été apportée au code ou à la configuration.

---

## Fichiers et emplacements référencés

- `C:\wamp64\www\videsgrenier\config\site.php` — NAP, adresse placeholder, FAQ
- `C:\wamp64\www\videsgrenier\resources\views\partials\jsonld-localbusiness.blade.php` — schema LocalBusiness + FAQPage
- `C:\wamp64\www\videsgrenier\resources\views\pages\commune.blade.php` — schema Service par commune + contenu localisé
- `C:\wamp64\www\videsgrenier\database\seeders\ContenuCommunesSeeder.php` — contenu unique des 19 pages commune
- `C:\wamp64\www\videsgrenier\resources\views\layouts\site.blade.php` — header/footer, téléphone visible, absence d'adresse visible
- `C:\wamp64\www\videsgrenier\app\Models\Temoignage.php` / `C:\wamp64\www\videsgrenier\app\Http\Controllers\PageController.php` — logique avis clients
- `C:\wamp64\www\videsgrenier\resources\views\pages\accueil.blade.php` (lignes 179-194) — section avis conditionnelle
