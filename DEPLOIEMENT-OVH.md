# Déploiement sur OVH mutualisé

Guide pas à pas pour mettre `videsgrenier.be` en ligne sur un hébergement web OVH mutualisé (Perso, Pro ou Performance). Le projet ne dépend d'aucune fonctionnalité indisponible sur ce type d'hébergement : PHP natif, MySQL, pas de processus Node.js à faire tourner.

## 0. Prérequis côté OVH

- **PHP 8.2 ou supérieur** — dans l'espace client, *Multisite → cliquer sur le domaine → Configurer → Version de PHP*. Laravel 12 refuse de démarrer en dessous de 8.2.
- **SSH activé** — disponible sur tous les plans d'hébergement web OVH actuels (icône SSH dans *Multisite* ou onglet *FTP-SSH*). Indispensable pour `composer`, `artisan` et le lien de stockage.
- **Une base MySQL** créée depuis *Hébergements → Bases de données → Créer une base*. Notez le nom de la base, l'utilisateur et l'hôte (`xxxxx.mysql.db`) affichés une fois créée.

## 1. Base de données

1. Dans phpMyAdmin (accessible depuis la fiche de la base dans l'espace client), onglet **Importer**.
2. Sélectionnez [`database/sql/videsgrenier-ovh.sql`](database/sql/videsgrenier-ovh.sql) — c'est l'export du schéma complet **sans** `CREATE DATABASE`/`USE` (l'utilisateur OVH n'a pas ce droit, phpMyAdmin vous place déjà dans la bonne base) et **sans** compte admin (le hash n'a jamais à transiter par un fichier commité).
3. Vérifiez après import : 10 tables, 19 lignes dans `communes`, 6 dans `services`, 6 dans `articles`.

## 2. Fichiers

Deux options, selon ce que permet votre plan :

**Avec SSH + Git (recommandé)**
```bash
ssh votre-login@ssh.cluster0XX.hosting.ovh.net
cd www
git clone <url-de-votre-dépôt> videsgrenier
cd videsgrenier
composer install --no-dev --optimize-autoloader
```

**Sans accès Git (FTP)**
En local : `composer install --no-dev --optimize-autoloader`, puis uploadez **tout le dossier**, `vendor/` compris (composer n'est pas disponible en FTP seul), vers `www/videsgrenier` sur le serveur. Exclure de l'upload : `.env`, `.git/`, `storage/logs/*`, `node_modules/` (absent de ce projet — pas de build front à part `style.css`, déjà servi en statique).

## 3. Pointer le domaine sur `/public`

Le document root doit être `public/`, jamais la racine du projet — sinon `.env`, `composer.json` et le code applicatif seraient directement téléchargeables.

Dans l'espace client : *Multisite → votre domaine → Modifier → Dossier* = `videsgrenier/public`.

Si votre plan ne permet pas de choisir un sous-dossier comme cible, utilisez le fallback fourni : renommez [`.htaccess.ovh-racine.example`](.htaccess.ovh-racine.example) en `.htaccess` et placez-le à la racine de l'hébergement (à côté du dossier `videsgrenier`, pas dedans).

## 4. Configuration (`.env`)

1. Copiez [`.env.production.example`](.env.production.example) en `.env` à la racine du projet sur le serveur.
2. Remplissez `DB_HOST` / `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` avec les valeurs de l'étape 1.
3. Renseignez un vrai fournisseur SMTP transactionnel (`MAIL_*`) — Postmark ou Brevo comme prévu au spec §2. **Sans ça, les e-mails de devis ne partiront jamais**, ils resteront silencieusement en échec.
4. `APP_URL` = l'URL réelle du site (`https://www.videsgrenier.be`), pas `localhost`.
5. `APP_DEBUG=false` impérativement — sinon les erreurs affichent des chemins serveur et des extraits de code aux visiteurs.

## 5. Finalisation via SSH

```bash
cd www/videsgrenier
php artisan storage:link
php artisan optimize
chmod -R 775 storage bootstrap/cache
```

`storage:link` crée le lien symbolique `public/storage → storage/app/public` : sans lui, les photos de réalisations et de galeries uploadées depuis l'admin renverront des 404.

## 6. Créer le compte admin en production

Ne réutilisez jamais le mot de passe temporaire créé en local — il a circulé en clair pendant le développement.

```bash
php artisan admin:mot-de-passe admin@videsgrenier.be --nom="Yassinos"
```

La commande demande une saisie masquée, jamais stockée dans l'historique shell ni dans un fichier.

## 7. SSL

*Multisite → votre domaine → SSL* → activer le certificat Let's Encrypt gratuit d'OVH. Une fois actif, forcez `SESSION_SECURE_COOKIE=true` dans le `.env` (déjà réglé dans le modèle).

## 8. À corriger avant l'ouverture publique

Ces points ne sont pas techniques mais bloquent une mise en ligne sérieuse :

- **[config/site.php](config/site.php)** contient une adresse fictive (« Rue de la Loi 1 ») publiée dans le JSON-LD `LocalBusiness` de chaque page. Remplacez-la par la vraie adresse — elle doit être identique à celle de votre fiche Google Business Profile (cohérence NAP, spec §5.1).
- **Fiche Google Business Profile** à créer si ce n'est pas déjà fait (spec §5.1) — hors périmètre du code, à faire dans votre compte Google.
- **`reglages.ga_id`** est à `G-XXXXXXX` : tant qu'il n'est pas remplacé par un vrai identifiant GA4 depuis `/admin/reglages`, ni le bandeau cookies ni le suivi de conversion ne s'activent (comportement volontaire, pas un bug).
- **Réseaux sociaux et fond vidéo/slider** (`/admin/reglages`) sont vides par défaut — à renseigner une fois en ligne si vous voulez les icônes Facebook/Instagram/TikTok en pied de page et un fond animé personnalisé.
- **Upload vidéo** : la limite applicative est fixée à 50 Mo, mais `upload_max_filesize`/`post_max_size` d'OVH peuvent être plus bas par défaut. Si l'upload échoue en silence, augmentez ces valeurs via un fichier `.user.ini` à la racine de `public/` (OVH les prend en compte automatiquement, pas besoin d'accès root) — ou utilisez un lien YouTube à la place, qui n'a pas cette limite.

## 9. Checklist de vérification post-déploiement

- [ ] `/` , `/services`, `/debarras/ixelles`, `/realisations`, `/galerie`, `/blog` répondent 200
- [ ] Soumission du formulaire `/devis` → redirection vers `/devis/merci` → ligne visible dans `/admin/devis`
- [ ] E-mail de notification et accusé de réception effectivement reçus (teste le SMTP, pas seulement le code)
- [ ] `/admin/login` accessible, connexion avec le compte créé à l'étape 6
- [ ] `/sitemap.xml`, `/robots.txt`, `/llms.txt` répondent 200 et ne contiennent aucune URL `/admin`
- [ ] Upload d'une photo dans `/admin/realisations` ou `/admin/galeries` → l'image s'affiche bien côté public (valide `storage:link`)
- [ ] Cadenas SSL actif dans le navigateur
