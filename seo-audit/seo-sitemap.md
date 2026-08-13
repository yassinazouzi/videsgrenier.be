# Audit Sitemap XML — Videsgrenier.be

**Date de l'audit :** 2026-08-13
**Environnement :** local (http://localhost/videsgrenier/public/) — pas encore soumis à Google Search Console, comportement normal pour un environnement non déployé.
**Source :** `sitemap()` dans `app/Http/Controllers/SeoController.php` + vue `resources/views/seo/sitemap.blade.php`
**Mode :** lecture seule, aucun fichier modifié.

---

## Résumé exécutif

| Sévérité | Nombre de constats |
|---|---|
| Critical | 0 |
| High | 2 |
| Medium | 3 |
| Low | 2 |
| Info | 0 (fusionné dans Low) |
| Pass | 8 |

Le sitemap est globalement propre (XML valide, pas d'URLs admin, comptage exact) mais deux problèmes méritent une correction avant mise en production : des erreurs 500 intermittentes liées à la table `sessions`, et deux pages publiques indexables absentes du sitemap (`/blog`, `/galerie`).

---

## 1. Constats — classés par sévérité

### HIGH

**H1 — Erreurs HTTP 500 intermittentes sur des URLs du sitemap (risque de crawl)**
- Constat : sur les 40 URLs testées, `/debarras/ixelles` et `/blog/prix-debarras-bruxelles` ont chacune renvoyé un `500` lors du premier passage, puis `200` de façon stable sur 5 tentatives suivantes.
- Cause identifiée dans `storage/logs/laravel.log` :
  ```
  PDOException: SQLSTATE[42S02]: Base table or view not found: 1146
  La table 'videsgrenier.sessions' n'existe pas
  ```
  Le driver de session est `database` mais la table `sessions` n'existe pas de façon fiable au moment de la requête (migration manquante ou table supprimée/recréée). Comme `curl` sans cookie-jar ouvre une nouvelle session à chaque requête, **n'importe quelle page du site peut potentiellement déclencher ce 500**, pas seulement les deux URLs testées ici.
- Impact SEO : si Googlebot crawle une URL du sitemap au moment où cette erreur se produit, la page peut être marquée en erreur serveur dans Search Console et perdre en fréquence de crawl. Ce n'est pas un défaut du sitemap lui-même, mais cela contredit directement le check "toutes les URLs listées répondent 200" et doit être corrigé avant mise en production.
- Action recommandée : `php artisan session:table && php artisan migrate` (ou vérifier pourquoi la table est absente de façon intermittente — possible script de reset DB tournant en parallèle en local), puis re-tester l'ensemble des 40 URLs sur plusieurs passages.

**H2 — Pages publiques indexables absentes du sitemap**
- `/galerie` (route `galeries.index`) → HTTP 200, aucune balise `noindex`, page réelle ("Galeries photo — nos débarras à Bruxelles").
- `/blog` (route `blog.index`) → HTTP 200, aucune balise `noindex`, page listant les 6 articles publiés.
- Ces deux pages existent dans `routes/web.php`, sont linkées dans le menu principal (header/footer du site), retournent 200 et n'ont pas de meta robots restrictif — elles devraient donc figurer dans le sitemap au même titre que `services.index` ou `realisations.index`.
- Action recommandée : ajouter dans `SeoController::sitemap()` :
  ```php
  ['loc' => route('galeries.index'), 'priority' => '0.5', 'changefreq' => 'monthly'],
  ['loc' => route('blog.index'), 'priority' => '0.7', 'changefreq' => 'weekly'],
  ```

### MEDIUM

**M1 — L'URL de la page d'accueil dans le sitemap renvoie un 301, pas un 200**
- Le sitemap liste `http://localhost/videsgrenier/public` (sans slash final).
- Cette URL renvoie un `301 Moved Permanently` vers `http://localhost/videsgrenier/public/` (avec slash final).
- Le tag canonical de la page finale pointe cependant vers l'URL **sans** slash (`http://localhost/videsgrenier/public`), donc Google finira par comprendre la situation, mais cela crée un statut "Page avec redirection" inutile dans Search Console au lieu d'un statut "Indexée" propre dès le premier crawl.
- Cause probable : `APP_URL=http://localhost/videsgrenier/public` (sans slash final) dans `.env`, combiné à `route('accueil')` qui génère l'URL brute sans normaliser le slash, alors que le serveur redirige les requêtes racine vers la version avec slash.
- Action recommandée : soit ajouter le slash final à `APP_URL`, soit générer explicitement l'URL canonique finale dans le sitemap (`rtrim(route('accueil'), '/') . '/'` ou équivalent), afin que l'URL sitemap = URL canonical = URL 200.

**M2 — `lastmod` basé sur une date de création qui ne sera jamais mise à jour**
- `Realisation` et `Article` ont tous les deux `const UPDATED_AT = null;` dans leur modèle Eloquent — aucune colonne de suivi des modifications n'est renseignée par Eloquent.
- Le sitemap utilise `cree_le` (réalisations) et `publie_le` (articles) comme `lastmod`. Ces valeurs sont correctes au moment de la publication, mais **si un article ou une réalisation est modifié après publication (correction de texte, changement de photo, etc.), le `lastmod` du sitemap ne bougera jamais** puisqu'aucune colonne "modifié le" n'existe/n'est mise à jour.
- Impact : signal de fraîcheur potentiellement trompeur à moyen terme (Google peut ne pas re-crawler une page dont il pense — à tort — que le contenu n'a pas changé).
- Action recommandée : ajouter une vraie colonne de suivi de modification (`modifie_le` ou activer `UPDATED_AT` standard) et l'utiliser comme `lastmod`.

**M3 — Redondance/conflit mineur robots.txt vs meta noindex sur `/devis/merci`**
- `robots.txt` contient `Disallow: /devis/merci` **et** la page elle-même a `<meta name="robots" content="noindex,nofollow">`.
- Ce n'est pas une erreur bloquante ici (la page est déjà absente du sitemap, `grep -c "/devis/merci"` = 0, confirmé), mais c'est une incohérence de méthode : si un moteur respecte le `Disallow`, il ne peut techniquement jamais lire la balise `noindex` qu'il ne crawle pas. Google recommande de choisir une seule méthode (noindex meta suffit, pas besoin de bloquer via robots.txt) pour une page qui n'est de toute façon jamais linkée en dur.
- Action recommandée (non bloquante) : retirer `Disallow: /devis/merci` du robots.txt et garder uniquement la balise noindex sur la page, ou documenter volontairement le choix de double protection.

### LOW

**L1 — `priority` et `changefreq` : rappel qu'ils sont ignorés par Google**
- Le sitemap utilise des priorités graduées (1.0 accueil → 0.9 devis/communes → 0.8 services → 0.7 réalisations → 0.6 réalisations individuelles/articles → 0.5 contact/à-propos) de façon **cohérente en interne** (hiérarchie logique respectée, aucune valeur aberrante).
- Cependant, Google ignore officiellement `<priority>` et `<changefreq>` depuis 2020 (confirmé par la documentation Google elle-même). Mettre les 19 pages `/debarras/{commune}` à `0.9`, volontairement au même niveau que `/devis` (page de conversion), n'aura **aucun effet réel** sur le classement ou la fréquence de crawl côté Google.
- Ce n'est pas une erreur — l'intention stratégique (signaler que les pages communes sont le levier SEO principal) est louable et sans risque — mais le vrai levier pour faire remonter ces pages reste : maillage interne dense depuis la homepage/footer (déjà en place, confirmé dans le HTML), contenu unique par commune (~200+ mots différenciés), backlinks locaux. Les balises `priority`/`changefreq` pourraient être retirées sans aucune perte SEO réelle, pour simplifier le sitemap.
- Aucune valeur `hourly` ou `always` détectée sur les pages statiques — ce point spécifique du check est conforme (PASS).

**L2 — `changefreq: monthly` sur les articles de blog déjà publiés**
- Les 6 articles de blog ont `changefreq: monthly`, alors qu'un article de blog publié change rarement après publication (`yearly` serait plus réaliste). Impact nul puisque Google ignore ce champ (cf. L1) — mentionné uniquement pour la forme/cohérence interne.

---

## 2. Détail des checks demandés

### 1. XML bien formé
**PASS.** Validé via `DOMDocument` PHP : déclaration `<?xml version="1.0" encoding="UTF-8"?>` correcte, namespace `xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"` conforme au schéma officiel, racine `<urlset>` valide.

### 2. Statuts HTTP des URLs
39/40 URLs testées en 200 stable. 2 URLs (`/debarras/ixelles`, `/blog/prix-debarras-bruxelles`) ont montré un `500` intermittent au premier passage — voir **H1**. L'URL homepage renvoie `301` (redirection vers la version avec slash) plutôt qu'un `200` direct — voir **M1**. Détail complet :

| URL | Code | Note |
|---|---|---|
| `/` (racine du sitemap) | 301 | → M1 |
| `/services`, `/realisations`, `/devis`, `/contact`, `/a-propos` | 200 | OK |
| 6× `/services/{slug}` | 200 | OK |
| 19× `/debarras/{commune}` | 200 (1 flaky) | → H1 pour ixelles |
| 3× `/realisations/{slug}` | 200 | OK |
| 6× `/blog/{slug}` | 200 (1 flaky) | → H1 pour prix-debarras-bruxelles |

### 3. Cohérence des priorités (0.9 sur les 19 communes)
Cohérent en interne avec la hiérarchie du site (voir **L1**). C'est un choix stratégique légitime et documenté dans le code (`// Les 19 pages communes sont le cœur du SEO local : priorité haute.`), sans risque, mais son effet réel sur Google est nul puisque `priority` est ignoré. Le vrai travail SEO doit porter sur le contenu et le maillage, pas sur cette balise.

### 4. changefreq réalistes
**PASS.** Aucune valeur `hourly` ou `always` détectée. Valeurs utilisées : `weekly`, `monthly`, `yearly` — toutes plausibles. Remarque mineure sur les articles de blog (**L2**).

### 5. Pages manquantes vs `routes/web.php`
Comparaison de toutes les routes `GET` publiques (hors `/admin/*`) :

| Route | Dans le sitemap ? | Verdict |
|---|---|---|
| `/` | Oui | OK |
| `/services`, `/services/{service}` | Oui | OK |
| `/debarras/{commune}` | Oui (19) | OK |
| `/realisations`, `/realisations/{realisation}` | Oui | OK |
| `/galerie` (index) | **Non** | → **H2** |
| `/galerie/{galerie}` | Oui (0 publiées actuellement) | OK, cohérent avec "hero-accueil" non publiée |
| `/blog` (index) | **Non** | → **H2** |
| `/blog/{article}` | Oui (6) | OK |
| `/devis` | Oui | OK |
| `/devis/merci` | Non (volontaire) | OK, `noindex` + exclu, cf. **M3** |
| `/contact`, `/a-propos` | Oui | OK |
| `/mentions-legales` | Non | OK — page a `noindex,nofollow`, exclusion justifiée |
| `/confidentialite` | Non | OK — page a `noindex,nofollow`, exclusion justifiée |
| `/sitemap.xml`, `/robots.txt`, `/llms.txt` | Non | OK, pages utilitaires, non concernées |
| `/admin/*` | Non | OK, confirmé absent |

### 6. Absence d'URLs admin/noindex
**PASS.**
- `grep -c "/admin"` → **0**
- `grep -c "/devis/merci"` → **0**

### 7. Présence de `lastmod`
**PASS avec réserve.** `lastmod` présent sur les 3 réalisations et les 6 articles, avec des dates réellement variées (non identiques, de `2026-07-21` à `2026-08-12`) — bon point, ce n'est pas une date fixe/factice. Absence de `lastmod` sur les pages statiques/services/communes est correcte par défaut (pas de date fiable à fournir plutôt que d'en inventer une). Réserve : voir **M2** sur la fiabilité de `lastmod` dans le temps (pas de mise à jour si le contenu est édité après publication).

### 8. Sitemap référencé dans robots.txt
**PASS.** `curl -s .../robots.txt | grep -i sitemap` retourne :
```
Sitemap: http://localhost/videsgrenier/public/sitemap.xml
```

### 9. Limite de taille (50 000 URLs / 50 Mo)
**PASS, très largement respecté.** 40 URLs, fichier de 7 006 octets (~7 Ko). Aucune action nécessaire à ce stade ; à surveiller uniquement si le nombre de communes, services ou articles venait à croître de façon importante (peu probable vu le périmètre géographique fixe de 19 communes bruxelloises).

### 10. Comptage total des URLs
**PASS — comptage exact.**
```
6 pages statiques (accueil, services, réalisations, devis, contact, à-propos)
+ 6 services actifs
+ 19 communes actives
+ 3 réalisations publiées
+ 0 galeries publiées (galerie "hero-accueil" volontairement non publiée — confirmé : page /galerie affiche "galeries arrivent bientôt")
+ 6 articles publiés
= 40 URLs
```
`grep -c "<url>"` et `grep -c "<loc>"` sur le sitemap confirment bien **40**.

---

## 3. Fichiers consultés (lecture seule)

- `C:\wamp64\www\videsgrenier\app\Http\Controllers\SeoController.php`
- `C:\wamp64\www\videsgrenier\resources\views\seo\sitemap.blade.php`
- `C:\wamp64\www\videsgrenier\routes\web.php`
- `C:\wamp64\www\videsgrenier\app\Models\Realisation.php`
- `C:\wamp64\www\videsgrenier\app\Models\Article.php`
- `C:\wamp64\www\videsgrenier\storage\logs\laravel.log`
- `C:\wamp64\www\videsgrenier\.env` (lecture de `APP_URL` uniquement)
- Endpoints testés en direct : `http://localhost/videsgrenier/public/sitemap.xml`, `/robots.txt`, et les 40 URLs du sitemap + 6 URLs hors sitemap (`/galerie`, `/blog`, `/mentions-legales`, `/confidentialite`, `/devis/merci`, `/admin`)

---

## 4. Priorités de correction recommandées

1. **H1** — Corriger l'erreur de table `sessions` manquante (risque de 500 aléatoires sur tout le site, pas que le sitemap).
2. **H2** — Ajouter `/galerie` et `/blog` au sitemap.
3. **M1** — Aligner l'URL sitemap de la homepage sur l'URL canonique finale (avec slash).
4. **M2** — Ajouter un vrai suivi de date de modification pour un `lastmod` fiable dans le temps.
5. **M3** — Simplifier la politique robots.txt/noindex sur `/devis/merci` (cosmétique, non bloquant).
6. **L1/L2** — Optionnel : retirer `priority`/`changefreq` du sitemap (aucun effet Google) ou les laisser tels quels, sans risque.

Aucun fichier n'a été modifié durant cet audit (lecture seule).
