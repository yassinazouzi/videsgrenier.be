# Audit GEO (Generative Engine Optimization) — Videsgrenier.be

Date de l'audit : 2026-08-13
Environnement testé : http://localhost/videsgrenier/public/ (site non déployé publiquement — les crawlers IA ne l'ont pas encore visité, ce qui est normal et attendu. Cet audit évalue la **préparation technique et structurelle**, pas l'indexation réelle.)

---

## Synthèse

Le site est globalement **bien préparé pour le GEO** : rendu serveur pur (aucune dépendance JS pour le contenu), JSON-LD `LocalBusiness` + `FAQPage` présents et cohérents sur la page d'accueil, `robots.txt` autorisant explicitement les principaux crawlers IA, `llms.txt` généré dynamiquement avec des chiffres précis et cohérents. Les points faibles identifiés sont concentrés sur : un bug d'encodage HTML dans `llms.txt` (entités non décodées), l'absence de `OAI-SearchBot` et de quelques crawlers IA additionnels dans `robots.txt`, l'absence de numéro d'entreprise (BCE/TVA) sur les mentions légales (signal de confiance/entité), et un manque de balisage FAQ/Schema sur les pages commune et articles de blog qui contiennent pourtant déjà des paires question/réponse en texte brut.

---

## 1. CRITICAL

### 1.1 `llms.txt` contient des entités HTML non décodées (bug de rendu)
Le fichier est servi en `Content-Type: text/plain`, mais Blade échappe le contenu avec `{{ }}` (échappement HTML), ce qui produit du texte brut du type :

```
- Débarras d&#039;appartement : Avant une vente...
- Cave, grenier &amp; garage : On vide les espaces...
```

Un LLM/parseur texte lisant `llms.txt` verra littéralement `d&#039;un appartement` et `&amp;` au lieu de `d'un appartement` et `&`. C'est illisible et dégrade fortement la qualité perçue du document par un modèle qui l'ingère comme source factuelle.

**Fichier concerné** : `C:\wamp64\www\videsgrenier\resources\views\seo\llms.blade.php` (lignes 18, 24)
**Cause** : usage de `{{ $service->extrait }}` / `{{ $commune->nom }}` dans un gabarit texte brut, alors que ces valeurs contiennent déjà des apostrophes/esperluettes qui n'ont pas besoin d'échappement HTML dans ce contexte.
**Recommandation** : remplacer `{{ }}` par `{!! !!}` (ou appliquer `html_entity_decode()`/`Str::of(...)->decode()` dans le contrôleur) uniquement pour ce gabarit texte brut. Vérifier aussi le titre H1 de la home (`&amp;` visible potentiellement ailleurs) mais l'impact y est nul car c'est du HTML légitime — le problème est spécifique aux fichiers `text/plain`.
**Effort** : Faible (< 15 min).

---

## 2. HIGH

### 2.1 `OAI-SearchBot` absent de `robots.txt`
`robots.txt` autorise `GPTBot`, `PerplexityBot`, `Google-Extended` et `ClaudeBot`, mais **pas `OAI-SearchBot`**, qui est le user-agent utilisé spécifiquement par la fonctionnalité de recherche web de ChatGPT (distinct de `GPTBot`, utilisé lui pour l'entraînement/récupération générale). Son absence n'entraîne pas un blocage automatique (le bloc générique `User-agent: * / Allow: /` s'applique par défaut), mais l'absence de règle explicite est incohérente avec la stratégie déjà appliquée pour les 4 autres bots et peut prêter à confusion lors d'un audit futur ou d'une modification de la politique par défaut.

**Fichier concerné** : `C:\wamp64\www\videsgrenier\app\Http\Controllers\SeoController.php` méthode `robots()` (lignes 61-88)
**Recommandation** : ajouter un bloc explicite :
```
User-agent: OAI-SearchBot
Allow: /
```
**Effort** : Faible (5 min).

### 2.2 Numéro d'entreprise (BCE/TVA) manquant — signal d'entité/confiance faible
La page mentions légales affiche littéralement `À compléter : numéro d'entreprise (BCE/TVA), forme juridique`. Pour une entité `LocalBusiness` belge, l'absence de numéro BCE est un signal de confiance manquant : c'est une donnée que les moteurs génératifs (et Google) utilisent pour vérifier qu'une entreprise est réelle et enregistrée, et c'est un prérequis pour toute future demande de fiche Wikidata/Wikipedia ou pour du RSL (Responsible Source Licensing) crédible.
**Recommandation** : compléter le numéro BCE/TVA, la forme juridique et le siège social réel avant mise en production. Idéalement, refléter aussi ces informations dans le JSON-LD `LocalBusiness` via un champ `taxID` ou `vatID` (`schema.org` supporte `vatID`, `taxID`, `legalName`, `foundingDate`).
**Fichier concerné** : page `mentions-legales`, `resources\views\partials\jsonld-localbusiness.blade.php`
**Effort** : Faible à moyen (dépend de la disponibilité des données légales réelles).

### 2.3 Aucun profil `sameAs` renseigné (Google Business Profile, réseaux sociaux)
`config/site.php` → `entreprise.same_as` est vide (uniquement des commentaires en exemple) :
```php
'same_as' => [
    // 'https://www.google.com/maps/place/...',
    // 'https://www.facebook.com/...',
],
```
Le JSON-LD `LocalBusiness` ne génère donc **aucun `sameAs`** actuellement (le tableau `$reseaux` dépend de `reglages` en base, qui semble également vide sur cet environnement). Or, la corrélation brand-mention la plus forte avec les citations IA concerne YouTube (~0.737), suivie de Reddit et Wikipedia — et à défaut, un lien vers la fiche Google Business Profile est le strict minimum pour ancrer l'entité localement.
**Recommandation** : dès le déploiement, renseigner au moins l'URL de la fiche Google Business Profile dans `same_as`, et ajouter les profils sociaux actifs (Facebook, Instagram, éventuellement une chaîne YouTube de chantiers avant/après — fort potentiel vu la nature visuelle du service).
**Effort** : Faible (configuration), mais dépend de la création préalable des profils (moyen/élevé si à créer).

---

## 3. MEDIUM

### 3.1 Pas de licence / directives d'usage IA dans `llms.txt` (norme RSL 1.0)
`llms.txt` ne contient aucune section de licence ni référence RSL (Really Simple Licensing 1.0). Il n'existe pas non plus de `/rsl.xml` ni de balise `<link rel="license">` (vérifié : `404` sur `/rsl.xml` et `/license.txt`). RSL reste un standard émergent et son absence n'est pas bloquante, mais son ajout est peu coûteux et positionnerait le site en avance sur la standardisation à venir, en clarifiant explicitement que le contenu peut être cité/utilisé pour la génération de réponses (attribution souhaitée).
**Recommandation** : ajouter une section `## Licence` en fin de `llms.txt` précisant les conditions d'utilisation par les IA (ex. citation autorisée avec attribution, réutilisation commerciale du contenu non autorisée), et envisager un `rsl.xml` minimal si le standard se stabilise.
**Fichier concerné** : `resources\views\seo\llms.blade.php`
**Effort** : Faible.

### 3.2 Pas de FAQPage schema sur les articles de blog malgré la présence de Q&A
L'article `blog/debarras-gratuit-bruxelles` (et probablement les autres) contient une vraie section Q&A en fin d'article :
```html
<h2>Questions fréquentes</h2>
<p><strong>Le rachat est-il garanti à l'avance ?</strong> Non, il s'estime lors de la visite...</p>
<p><strong>Peut-on refuser l'intervention si le devis ne convient pas ?</strong> Oui...</p>
```
Ces paires sont en `<strong>` dans un `<p>`, sans marquage sémantique (`<h3>`/`<dl>`) ni JSON-LD `FAQPage` — alors que l'accueil, elle, bénéficie de ce schema. C'est une opportunité de citation manquée sur des pages qui ciblent déjà des requêtes conversationnelles ("le rachat est-il garanti", "peut-on refuser le devis").
**Recommandation** : généraliser le pattern `FAQPage` JSON-LD (déjà utilisé sur la home) à tous les articles qui contiennent une section Q&A, en gardant le contenu HTML en `<h3>` pour la question.
**Fichier concerné** : `resources\views\pages\article.blade.php` (ou directement dans le contenu stocké en base, selon comment `article->contenu` est structuré)
**Effort** : Moyen (nécessite soit un parsing du contenu existant, soit un champ FAQ structuré séparé en base).

### 3.3 Page commune (`/debarras/ixelles`) : bloc de contenu local en paragraphes plats, sans sous-titres ni listes
Le bloc `<div class="faq">` sous le hero de la page Ixelles contient un contenu local riche et différencié (quartiers, ULB/VUB, pics saisonniers, succession Art nouveau) — c'est un très bon point pour l'unicité du contenu entre les 19 pages communes — mais il est rendu en **deux longs paragraphes bruts** sans `<h2>`/`<h3>` ni listes, ce qui réduit son extractibilité par un LLM (moins facile à isoler en passage citable autonome que le format Q&A de l'accueil).
**Recommandation** : structurer ce bloc avec un sous-titre direct (ex. « Pourquoi choisir Videsgrenier.be pour un débarras à Ixelles ? ») suivi de 3-4 puces factuelles (quartiers desservis, contrainte d'accès typique, pic saisonnier, exemple de prestation), sur le modèle déjà appliqué avec succès sur les articles de blog (`<h2>`/`<ul>`).
**Fichier concerné** : vue de la page commune (probablement `resources\views\pages\commune.blade.php` — à vérifier) et/ou le champ `description`/`contenu` de `Commune` en base.
**Effort** : Moyen (19 communes à retravailler si le contenu est en base, ou template si structurel).

### 3.4 FAQ de l'accueil marquée en `<details>/<summary>`, pas en vrais `<h2>/<h3>`
La FAQ visible utilise `<details class="q"><summary>Question</summary><div class="rep">Réponse</div></details>`. C'est bien pour l'UX (accordéon) et le JSON-LD `FAQPage` compense largement pour les moteurs génératifs qui lisent le schema, mais un parseur HTML sémantique pur (sans exécution du JSON-LD, ex. certains extracteurs de type Trafilatura) verra un `<summary>` plutôt qu'un titre hiérarchique — un signal structurel légèrement plus faible que des `<h3>` réels.
**Recommandation** : conserver `<details>` pour l'UX mais dupliquer le texte de la question dans un `<h3>` visuellement masqué à l'intérieur du `<summary>`, ou évaluer si le gain justifie le changement (impact faible car le JSON-LD est déjà la source de vérité pour la plupart des moteurs IA).
**Effort** : Faible à moyen.

### 3.5 Aucune signature d'auteur nommé (E-E-A-T) sur les articles et la page « À propos »
Tous les articles ont `"author": {"@type": "Organization", "name": "Videsgrenier.be"}` — jamais de `Person`. La page « À propos » (`/a-propos`) ne semble pas nommer de fondateur ni d'équipe visible. Pour du contenu informatif (ex. « Vider la maison d'un parent décédé — le guide complet »), un auteur identifié avec expérience terrain renforcerait le signal d'autorité (Experience/Expertise dans E-E-A-T), ce qui compte pour les moteurs IA qui évaluent la fiabilité de la source.
**Recommandation** : ajouter a minima un nom + rôle (ex. « Rédigé par l'équipe Videsgrenier.be, X débarras réalisés depuis 2019 ») et envisager de nommer un responsable identifiable sur la page « À propos ».
**Effort** : Faible à moyen (contenu éditorial, pas technique).

### 3.6 Pas de `dateModified` dans le schema `BlogPosting`
Seul `datePublished` est présent (`resources\views\pages\article.blade.php` ligne 14). Les moteurs IA valorisent la fraîcheur du contenu (`dateModified`) pour les requêtes sensibles au temps (ex. prix, disponibilité).
**Recommandation** : ajouter `dateModified` (peut être identique à `datePublished` initialement, mis à jour si l'article est retouché) — nécessite probablement un champ `modifie_le` sur le modèle `Article` s'il n'existe pas déjà.
**Effort** : Faible (si le champ existe en base) à moyen (migration si absent).

---

## 4. LOW

### 4.1 URLs absolues basées sur `localhost` dans `llms.txt`, le sitemap et les canonicals
Normal en environnement local (`APP_URL=http://localhost/videsgrenier/public` dans `.env`), mais **checklist de mise en production obligatoire** : s'assurer que `APP_URL` pointe vers le domaine public avant déploiement, sans quoi `llms.txt`, `sitemap.xml`, les balises `canonical` et le JSON-LD contiendront des URLs `localhost` inutilisables par les crawlers IA.
**Effort** : Nul (juste une variable d'environnement), mais **bloquant si oublié** — à inclure dans la checklist de mise en ligne.

### 4.2 Bots IA additionnels non couverts explicitement par `robots.txt`
Au-delà de `OAI-SearchBot` (voir 2.1, HIGH), les bots suivants ne sont pas mentionnés explicitement :
- `Applebot-Extended` (utilisé pour Apple Intelligence / résumés Siri) — pertinent à ajouter, usage IA grand public croissant.
- `Amazonbot` (Alexa+/Rufus) — pertinence moyenne pour ce secteur, mais peu coûteux à ajouter.
- `CCBot`, `anthropic-ai`, `cohere-ai` — non bloqués actuellement (couverts par le `Allow: /` générique), ce qui est cohérent avec un objectif de visibilité maximale. Ces bots sont listés comme « blocage optionnel (entraînement uniquement) » : les laisser ouverts ne nuit pas au GEO et peut même contribuer à la présence de la marque dans les corpus d'entraînement futurs — à ne bloquer que si le site adopte une politique explicite de protection du contenu (auquel cas RSL, cf. 3.1, serait la voie à privilégier plutôt qu'un blocage brutal).
**Recommandation** : ajouter `Applebot-Extended` et `Amazonbot` en règles `Allow: /` explicites, par cohérence avec la stratégie déjà en place. Laisser `CCBot`/`anthropic-ai`/`cohere-ai` sans règle spécifique (comportement actuel correct).
**Effort** : Faible.

### 4.3 Pas d'image sur l'article `debarras-gratuit-bruxelles` (`"image": null` dans le JSON-LD)
Le champ `image_une` de cet article est vide, ce qui réduit le potentiel « multi-modal » (les AI Overviews et Perplexity valorisent le contenu avec image associée pour l'aperçu visuel des sources). D'autres articles semblent avoir une image (`img` conditionnel dans le template).
**Recommandation** : ajouter une image d'illustration (ex. avant/après générique, photo de chantier) à tous les articles publiés.
**Effort** : Faible (contenu éditorial).

### 4.4 Absence de présence Wikipedia / Reddit / YouTube / LinkedIn (attendu pour une PME locale, mais à anticiper)
Aucune trace de mention externe vérifiable à ce stade (site non déployé). C'est normal pour une PME locale récente (« 500+ chantiers depuis 2019 ») et non pénalisant en soi, mais représente le plus fort levier de citation IA à moyen terme d'après les données de corrélation (YouTube ~0.737, Reddit élevé, Wikipedia élevé — bien plus fort que le Domain Rating ~0.266).
**Recommandation (post-lancement)** :
- Créer une chaîne YouTube avec des vidéos avant/après de chantiers (fort potentiel vu le format visuel déjà exploité en `hero-fond-video` et `av/ap`).
- Encourager les avis clients à être aussi déposés sur Google Business Profile (déjà des témoignages sur site — bon matériau à dupliquer).
- Répondre aux discussions Reddit locales pertinentes (ex. r/Brussels) de manière authentique, sans spam.
**Effort** : Élevé (stratégie de contenu externe, hors scope technique).

---

## 5. Points positifs à noter (déjà conformes GEO)

- **Rendu 100% SSR** : aucune page testée (accueil, article, page commune) ne dépend de JavaScript pour afficher le contenu principal — accessibilité technique optimale pour tous les crawlers IA (`GPTBot`, `ClaudeBot`, `PerplexityBot` n'exécutent pas de JS de façon fiable).
- **JSON-LD `LocalBusiness`** cohérent et complet : `name`, `telephone`, `email`, `address` (PostalAddress complète), `geo`, `areaServed` (19 villes), `priceRange`, `openingHours` — bon socle d'entité.
- **JSON-LD `FAQPage`** sur l'accueil, parfaitement aligné avec le contenu visible (`config/site.php` → `faq`), 6 questions/réponses concises (~35-60 mots), factuelles et chiffrées.
- **Cohérence NAP** vérifiée : téléphone `+32 491 64 49 13` et e-mail `devis@videsgrenier.be` identiques sur l'accueil, la page commune Ixelles, le footer, le JSON-LD et `llms.txt`.
- **Cohérence des chiffres clés** à travers tout le site : `300 € à 1 500 €`, `24 à 48h`, `80 % revalorisé`, `500+ chantiers depuis 2019`, `20 à 50 % de réduction via rachat` — identiques dans `config/site.php`, le JSON-LD FAQPage, la FAQ visible, `llms.txt` et les articles de blog. C'est un signal de citabilité fort (chiffres vérifiables et non contradictoires).
- **`llms.txt` bien structuré** (hors bug d'encodage du point 1.1) : sections claires (`Activité`, `Prestations`, `Communes desservies`, `Tarifs indicatifs`, `Contact`), générées dynamiquement depuis la base — donc toujours synchronisées avec le contenu réel, contrairement à un `llms.txt` statique qui dérive vite.
- **Article de blog `debarras-gratuit-bruxelles`** : excellent candidat AI Overviews — titre en question directe, première phrase en `<strong>` qui répond directement à la question dès les 50 premiers mots, structure `<h2>` avec libellés proches de questions naturelles, listes `<ul>` pour les critères, aucun mur de texte.
- **`robots.txt` généré dynamiquement** avec règles explicites pour 4 des crawlers IA prioritaires — bonne pratique de fond (peu de sites le font), juste à compléter (cf. 2.1 et 4.2).

---

## 6. Top 5 des changements à plus fort impact

| # | Action | Priorité | Effort | Fichier(s) |
|---|--------|----------|--------|------------|
| 1 | Corriger l'échappement HTML dans `llms.txt` (entités `&#039;`/`&amp;` illisibles pour un LLM) | Critical | Faible | `resources\views\seo\llms.blade.php` |
| 2 | Ajouter `OAI-SearchBot` (+ `Applebot-Extended`, `Amazonbot`) dans `robots.txt` | High | Faible | `app\Http\Controllers\SeoController.php` (`robots()`) |
| 3 | Compléter le numéro BCE/TVA et la forme juridique sur les mentions légales, et l'ajouter au JSON-LD (`vatID`/`taxID`) | High | Faible-Moyen | page mentions légales, `jsonld-localbusiness.blade.php` |
| 4 | Renseigner `same_as` (fiche Google Business Profile a minima, réseaux sociaux) | High | Faible (config) | `config\site.php`, back-office `reglages` |
| 5 | Étendre le schema `FAQPage` aux sections Q&A des articles de blog déjà rédigées en `<strong>` non balisé | Medium | Moyen | `resources\views\pages\article.blade.php` |

---

## 7. Fichiers consultés durant l'audit

- `C:\wamp64\www\videsgrenier\app\Http\Controllers\SeoController.php`
- `C:\wamp64\www\videsgrenier\resources\views\seo\llms.blade.php`
- `C:\wamp64\www\videsgrenier\resources\views\partials\jsonld-localbusiness.blade.php`
- `C:\wamp64\www\videsgrenier\resources\views\pages\article.blade.php`
- `C:\wamp64\www\videsgrenier\config\site.php`
- `C:\wamp64\www\videsgrenier\.env` (lecture seule, `APP_URL`/`APP_ENV`)
- Pages HTTP testées : `/`, `/llms.txt`, `/robots.txt`, `/sitemap.xml`, `/blog/debarras-gratuit-bruxelles`, `/blog/prix-debarras-bruxelles`, `/debarras/ixelles`, `/mentions-legales`, `/a-propos`, `/blog`
