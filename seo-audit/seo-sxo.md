# Audit SXO — Videsgrenier.be

**Date de l'audit :** 13 août 2026
**Environnement analysé :** http://localhost/videsgrenier/public/ (site local, non déployé publiquement)
**Méthode :** lecture "backwards" théorique — pour chaque requête cible, on part de ce que Google favorise typiquement pour ce type d'intention (SERP consensus attendu), puis on compare avec ce que la page livre réellement. Aucune donnée SERP réelle n'a pu être utilisée (site non indexé).

**Score SXO** : cet audit évalue l'expérience de recherche (adéquation page/intention, friction, confiance) — il est **distinct** de tout score SEO technique classique.

---

## Résumé — Findings classés par sévérité

### 🔴 CRITICAL

1. **`/services/vide-maison` est un gabarit quasi vide alors que la requête appelle une page de service dense.**
   Pour "vide-maison Bruxelles", Google favorise des pages de service riches (process, ce qui est inclus, fourchette de prix, preuve sociale, FAQ). Ici, le H1 est suivi d'une seule phrase ("Débarras intégral d'une maison ou d'un appartement.") — texte identique à la meta description et à la carte de la page d'accueil — puis directement le formulaire et la liste des communes. Aucun contenu unique, aucune preuve sociale, aucun prix, aucune FAQ. C'est un mismatch de profondeur : la page se comporte comme un simple landing de formulaire dupliqué, pas comme une page de service qui peut rivaliser sur une requête commerciale à forte concurrence.

2. **Adresse NAP factice/institutionnelle dans le schema LocalBusiness (page d'accueil).**
   `"streetAddress":"Rue de la Loi 1", "postalCode":"1000"` avec les coordonnées géo correspondantes (50.8467, 4.3525) — c'est l'adresse du quartier gouvernemental/européen de Bruxelles (zone Rue de la Loi, siège du Premier ministre / institutions UE), clairement un placeholder de développement. Si ce schema part en production tel quel, c'est un signal de confiance local cassé (incohérence NAP avec Google Business Profile, risque de rejet ou de perte de confiance E-E-A-T), et cela affecte indirectement toutes les pages qui référencent la même entité `LocalBusiness`.

### 🟠 HIGH

3. **Page `/devis` (intention transactionnelle directe) sans aucun signal de confiance ni schema.**
   C'est la seule page auditée sans JSON-LD du tout, et sans preuve sociale (pas d'avis, pas de "500+ débarras réalisés"). Pour un visiteur qui arrive directement sur cette page via une recherche Google ("devis débarras Bruxelles"), sans être passé par la home, c'est le moment de plus forte intention — et aussi celui où la réassurance manque le plus.

4. **Contenu local `/debarras/ixelles` sans hiérarchie sémantique (pas de `<h2>`) et sans FAQ locale.**
   Le paragraphe de contexte local (Châtelain, Matonge, ULB, kots étudiants…) est injecté brut dans une `<div class="faq">` sans titre — bonne pertinence locale sur le fond, mais structure invisible pour l'extraction de passages Google et peu scannable pour l'utilisateur. Par ailleurs, la page ne reprend pas le schema `FAQPage` présent sur la home (ex. "combien coûte un débarras à Ixelles ?"), alors que c'est exactement le type de requête locale à forte conversion qui pourrait capter un rich result dédié.

### 🟡 MEDIUM

5. **Articles de blog (`prix-debarras-bruxelles`, `debarras-gratuit-bruxelles`) : bon contenu, mais CTA de conversion trop tardif et E-E-A-T faible.**
   La réponse chiffrée est excellente et bien positionnée (dès la première phrase, en gras — optimisée featured snippet). Mais le seul CTA proche de la réponse est un lien texte au milieu de l'article ; le vrai bloc CTA n'arrive qu'en toute fin de page. Autre point : l'auteur du `BlogPosting` est `"Organization"` générique, sans byline nommée ni expertise affichée — faible sur un sujet sensible (succession, argent).

6. **FAQ visible en HTML mais non balisée en `FAQPage` sur `debarras-gratuit-bruxelles`.**
   L'article contient deux questions/réponses claires ("Le rachat est-il garanti à l'avance ?", "Peut-on refuser l'intervention ?") en `<strong>` mais sans schema FAQPage, alors que la home l'utilise. Incohérence de traitement entre pages similaires.

### 🟢 LOW

7. **Page d'accueil : pas d'indication de prix visible avant la FAQ tout en bas.**
   Le persona "économe" doit scroller loin (jusqu'à la FAQ) ou aller sur le blog pour voir un ordre de grandeur (300 €–1 500 €). Un repère prix dans le hero ou juste sous les points de réassurance réduirait la friction.

8. **`/services/vide-maison` n'a aucun lien interne vers l'article prix ou vers les avis.**
   Faciliterait le maillage vers les pages qui répondent mieux à "combien ça coûte" et "peut-on faire confiance".

---

## Détail page par page

### 1. Page d'accueil — intention "débarras Bruxelles" (large, commerciale)

**Attente SERP théorique :** pack local (Maps/GBP) + sites de service avec devis immédiat, preuve sociale, confiance.

**Ce que la page livre :** H1 clair, CTA "Demander un devis" et formulaire "devis en 2 min" visibles dès le hero (vidéo de fond), bandeau permanent "Devis gratuit 24h" + rachat déduit. Preuve sociale solide plus bas : 500+ débarras, 19 communes, 80% revalorisé, 3 avis 5 étoiles, FAQ avec schema `FAQPage`, schema `LocalBusiness` complet (téléphone, horaires, zone de service).

- **Persona "propriétaire pressé qui compare 3 devis"** — Répond en <5s de scroll (CTA + formulaire dans le hero). Bon.
- **Persona "héritier en succession qui cherche à être rassuré"** — Le service "Succession & héritage" existe dans le menu déroulant et un avis mentionne une succession, mais rien sur l'assurance, l'agrément, la discrétion n'est mise en avant qu'implicitement. Signal de confiance moyen.
- **Persona "qui cherche à économiser au maximum"** — "Rachat déduit du prix" répété partout (bon signal), mais aucun repère de prix chiffré avant la FAQ tout en bas de page.

**Verdict : ALIGNÉ** (le type de page correspond à l'attente), mais confiance et repère prix perfectibles, et l'adresse NAP du schema est à corriger avant mise en ligne (voir Critical #2).

**Recommandations :**
- Remplacer l'adresse placeholder "Rue de la Loi 1" par la vraie adresse professionnelle (ou une zone de service générique sans adresse fixe si le business n'a pas de local).
- Ajouter un repère de prix ("à partir de 300 €") near le hero ou les points de réassurance, et un badge "assuré / agréé" visible sans scroller jusqu'à la FAQ.

---

### 2. `/debarras/ixelles` — intention "débarras Ixelles" (locale précise, forte intention)

**Attente SERP théorique :** l'utilisateur veut "quelqu'un pour débarrasser à Ixelles maintenant" — une page locale dédiée et actionnable, pas du contenu générique.

**Ce que la page livre :** Très bon signal local — H1 "Débarras & vide-maison à Ixelles", eyebrow "Ixelles · 1050", sous-titre citant des quartiers réels (Châtelain, Matonge, Flagey, ULB), formulaire de devis avec commune **pré-remplie "Ixelles"** (excellent, réduit la friction). Schema `Service` dédié avec `areaServed` ciblé sur Ixelles.

- **Persona "propriétaire pressé"** — Excellent : CTA + formulaire pré-rempli dès le hero, réponse immédiate à "je cherche quelqu'un à Ixelles maintenant".
- **Persona "héritier succession"** — Le paragraphe local mentionne explicitement "successions dans les maisons de maître du quartier Châtelain, riches en mobilier ancien" — bonne spécificité rassurante. Mais ce paragraphe n'a pas de titre (`<h2>` manquant), il se noie visuellement dans un bloc `.faq` mal nommé.
- **Persona "économe"** — Aucun repère prix spécifique à Ixelles (ex. étages sans ascenseur = plus de portage = coût différent, mentionné dans le blog prix mais pas ici). Pas de lien croisé vers l'article prix.

**Verdict : ALIGNÉ dans l'intention et la structure**, mais High sur la hiérarchie de contenu (voir Critical/High #4) et preuve sociale un peu mince (une seule réalisation, un seul avis, tous deux repris tel quels de la home).

**Recommandations :**
- Ajouter un `<h2>` visible (ex. "Débarras à Ixelles : ce qu'il faut savoir") avant le paragraphe de contexte local, et envisager un schema `FAQPage` local dédié ("Combien coûte un débarras à Ixelles ?").
- Ajouter au moins un deuxième témoignage/réalisation propre à Ixelles pour éviter l'effet de contenu recyclé de la home.

---

### 3. `/blog/prix-debarras-bruxelles` — intention "prix débarras Bruxelles" (informationnelle proche achat)

**Attente SERP théorique :** réponse chiffrée dès les premières lignes (optimisée featured snippet), idéalement en liste/tableau.

**Ce que la page livre :** Excellent sur ce point précis — première phrase du corps de texte : **"Un débarras à Bruxelles coûte généralement entre 300 € ... et 1 500 €..."**, en gras, sans avoir à scroller. Suivi d'une liste à puces claire par type de logement (cave 300-500€, appartement 1ch 500-800€, etc.) — format idéal pour extraction Google. Schema `BlogPosting` avec `datePublished` (fraîcheur).

- **Persona "pressé comparateur de devis"** — Réponse immédiate, mais le CTA de conversion n'apparaît qu'en lien texte au milieu de l'article puis dans un bloc CTA tout en bas — rien pour capter l'intention au moment exact où le prix vient d'être lu.
- **Persona "économe"** — Très bien servi : facteurs de variation, exemples concrets par type de logement, explication claire du mécanisme de rachat.
- **Persona "héritier succession"** — Contenu générique sur le prix, mais bon maillage vers l'article dédié "vider-maison-succession-bruxelles".

**Verdict : ALIGNÉ, un des meilleurs matchs page-type/intention de tout l'audit.**

**Recommandations :**
- Ajouter un mini-CTA (bandeau ou bouton) juste après le paragraphe de prix ("Obtenez votre prix exact sous 24h"), au lieu d'attendre la fin de page.
- Ajouter un schema `FAQPage` (les H2 "Comment obtenir un devis juste" s'y prêtent) et remplacer l'auteur générique "Organization" par une signature nommée/experte pour renforcer l'E-E-A-T sur un sujet lié à l'argent et à la succession.

---

### 4. `/blog/debarras-gratuit-bruxelles` — intention "débarras gratuit Bruxelles" (bonne affaire, risque de déception)

**Attente SERP théorique :** gérer la tension honnêtement sans décevoir immédiatement, garder le lecteur engagé vers la conversion.

**Ce que la page livre :** Très bien géré — première phrase honnête et nuancée dès le début : "Un débarras réellement gratuit existe, **mais seulement quand** la valeur de revente du contenu couvre entièrement le coût." Pas de sur-promesse, pas de déception brutale. Section "pourquoi les offres 100% gratuites méritent d'être vérifiées" positionne intelligemment l'entreprise comme plus fiable que la concurrence "gratuite" douteuse. CTA final reformulé en cohérence avec l'intention ("Demander une estimation gratuite de rachat" plutôt que "devis").

- **Persona "chasseur de bonne affaire"** — Bien géré : la tension est résolue tout de suite, sans dégoût ni sentiment d'arnaque, et le lecteur reste engagé car il comprend son cas précis.
- **Persona "pressé comparateur"** — Réponse claire, CTA cohérent, correct.
- **Persona "héritier succession"** — Non ciblé par cette page (normal, l'intention "gratuit" est différente), pas de mismatch à signaler ici.

**Verdict : ALIGNÉ**, bonne gestion éditoriale de la tension.

**Recommandations :**
- Les deux Q/R en fin d'article ("Le rachat est-il garanti à l'avance ?", "Peut-on refuser l'intervention ?") sont déjà rédigées en format FAQ — les baliser en schema `FAQPage` comme sur la home pour capter des rich results PAA.
- Même remarque que l'article prix sur l'auteur générique.

---

### 5. `/services/vide-maison` — intention "vide-maison Bruxelles" (service spécifique)

**Attente SERP théorique :** page de service dense — ce qui est inclus, déroulé, fourchette de prix, preuve sociale, FAQ — au même niveau d'exigence que pour une requête locale forte.

**Ce que la page livre :** Quasi rien. H1 "Vide-maison complète" suivi d'une seule phrase de description ("Débarras intégral d'une maison ou d'un appartement.") — **texte strictement identique** à la meta description et à la carte de service sur la page d'accueil. Puis directement le formulaire de devis et la liste des 19 communes en lien. Aucun paragraphe explicatif, aucune photo avant/après spécifique, aucun témoignage, aucune fourchette de prix, aucune FAQ, aucun lien vers l'article prix.

- **Persona "propriétaire pressé"** — Le CTA est là, mais rien pour construire la confiance avant de remplir un formulaire ; risque de rebond pour les visiteurs en phase de recherche plutôt que déjà décidés.
- **Persona "héritier succession" atterrissant sur cette page** — Zéro reconnaissance du contexte émotionnel (aucune mention de succession/deuil ici, alors que ce cas est fréquent pour un "vide-maison"), juste un formulaire froid.
- **Persona "économe"** — Aucune info prix, aucun lien vers l'article qui y répond pourtant très bien.

**Verdict : MISMATCH CRITIQUE.** Le type de page est correct sur le papier (page de service), mais son **niveau de profondeur** correspond à un simple gabarit de capture de lead, pas à une page de service capable de rivaliser sur une requête commerciale. C'est le finding le plus important de l'audit : c'est exactement le type de mismatch décrit dans la consigne — "une page qui devrait être un contenu de service riche mais se comporte comme un simple formulaire".

**Recommandations :**
- Ajouter un vrai corps de contenu unique (ce qui est inclus dans un vide-maison complet : cave, grenier, garage, tri, nettoyage final ; déroulé en 3 étapes déjà rédigé sur la home, à réutiliser/adapter ici), une fourchette de prix, et au moins un témoignage/avant-après spécifique au vide-maison.
- Ajouter des liens internes vers `/blog/prix-debarras-bruxelles` et `/blog/vider-maison-succession-bruxelles`, et un schema `FAQPage` propre à ce service. Répéter ce constat pour les autres pages `/services/*` non auditées ici (probable même gabarit, à vérifier).

---

### 6. `/devis` — intention transactionnelle directe "devis débarras Bruxelles"

**Attente SERP théorique :** pour une requête aussi directement transactionnelle, une page formulaire épurée est l'attente correcte (contrairement aux autres pages, ici un mismatch serait d'avoir *trop* de contenu, pas trop peu).

**Ce que la page livre :** Page formulaire propre, sans distraction, avec microcopy de réassurance ("prix ferme, sans engagement", "vos données ne sont jamais revendues"). Type de page cohérent avec l'intention.

- **Persona "pressé"** — Formulaire à 6 champs (nom, tél, email, prestation, commune, volume, message) ; correct mais la promesse "2 minutes" utilisée sur la home n'est pas reprise ici pour cadrer l'effort attendu.
- **Persona "héritier succession" arrivant directement via recherche** — Aucune reconnaissance du contexte, aucune réassurance spécifique visible sur cette page seule.
- **Point structurel notable :** c'est la **seule page auditée sans aucun JSON-LD** et sans aucune preuve sociale (pas d'avis, pas de "500+ débarras") — au moment de plus forte intention d'achat, c'est là que la réassurance manque le plus.

**Verdict : ALIGNÉ sur le type de page** (formulaire = bon choix pour cette intention), mais High sur l'absence totale de confiance/schema à ce stade critique du parcours (voir High #3).

**Recommandations :**
- Ajouter 1 ligne de preuve sociale près du formulaire ("500+ débarras réalisés à Bruxelles" ou un avis court) et réafficher le numéro de téléphone à côté du bouton d'envoi pour l'alternative "j'appelle plutôt que je remplis".
- Ajouter un schema minimal (`BreadcrumbList` + éventuellement `LocalBusiness` référencé) — actuellement absent alors que présent ailleurs.

---

## Synthèse des personas transverses

| Persona | Pages qui le servent bien | Pages qui le déçoivent |
|---|---|---|
| Propriétaire pressé, compare 3 devis | Accueil, Ixelles (commune pré-remplie), Devis | Vide-maison (pas de contenu de réassurance avant le formulaire) |
| Héritier en succession, cherche à être rassuré | Ixelles (mention Châtelain/successions), articles blog (maillage vers l'article succession) | Vide-maison et Devis (aucune reconnaissance du contexte) |
| Cherche à économiser au maximum | Blog prix, Blog gratuit (excellents) | Accueil (prix trop bas dans la page), Ixelles et Vide-maison (aucun repère prix) |

---

## Limitations de cet audit

- Site non déployé publiquement : **aucune donnée SERP réelle** (positions, featured snippets, PAA, pack local effectif) n'a pu être observée. L'analyse SERP est une lecture "backwards" théorique basée sur les patterns connus de Google pour ce type de requêtes, pas une observation empirique.
- Aucune vérification de la performance de rendu réelle (Core Web Vitals, vitesse de la vidéo hero) — non demandée, non évaluée ici.
- Le contenu du fichier `<video>` du hero (poids, format) n'a pas été audité en profondeur ; seule sa présence a été notée.
- Seules 6 pages ont été auditées ; les autres pages `/services/*` (débarras-appartement, cave-grenier, rachat-meubles, succession, nettoyage) suivent très probablement le même gabarit que `/services/vide-maison` et méritent le même contrôle — non vérifié directement ici, signalé par prudence.
- Le schema `LocalBusiness` (adresse, avis) n'a pas pu être confronté à un vrai Google Business Profile puisque le site n'est pas en ligne.

---

*Rapport généré en lecture seule — aucun fichier du projet n'a été modifié.*
