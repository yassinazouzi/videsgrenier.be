-- =====================================================================
--  VIDESGRENIER.BE — Base de données MySQL
--  Service de vide-grenier / débarras à Bruxelles
--  MySQL 8+ · InnoDB · utf8mb4
--  Exécuter :  mysql -u root -p < videsgrenier.sql
-- =====================================================================

CREATE DATABASE IF NOT EXISTS videsgrenier
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE videsgrenier;

SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================================
--  1. DEMANDES DE DEVIS  (table centrale — la conversion)
-- =====================================================================
DROP TABLE IF EXISTS devis;
CREATE TABLE devis (
  id             INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  nom            VARCHAR(150) NOT NULL,
  telephone      VARCHAR(30)  NOT NULL,
  email          VARCHAR(190),
  prestation     VARCHAR(80),
  commune        VARCHAR(80),
  message        TEXT,
  volume_estime  VARCHAR(120),
  source         VARCHAR(80),
  canal          ENUM('formulaire','whatsapp','telephone') DEFAULT 'formulaire',
  statut         ENUM('nouveau','contacte','devis_envoye','gagne','perdu') DEFAULT 'nouveau',
  montant_devis  DECIMAL(8,2),
  note_interne   TEXT,
  cree_le        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  maj_le         TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_devis_statut (statut, cree_le)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
--  2. SERVICES  (prestations éditables)
-- =====================================================================
DROP TABLE IF EXISTS services;
CREATE TABLE services (
  id               INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  slug             VARCHAR(120) NOT NULL UNIQUE,
  titre            VARCHAR(150) NOT NULL,
  icone            VARCHAR(20),
  extrait          VARCHAR(255),
  contenu          MEDIUMTEXT,
  ordre            TINYINT UNSIGNED DEFAULT 0,
  actif            BOOLEAN DEFAULT TRUE,
  meta_title       VARCHAR(190),
  meta_description VARCHAR(320)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
--  3. COMMUNES DE BRUXELLES  (pages SEO locales)
-- =====================================================================
DROP TABLE IF EXISTS communes;
CREATE TABLE communes (
  id               TINYINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  nom              VARCHAR(80) NOT NULL,
  slug             VARCHAR(80) NOT NULL UNIQUE,
  code_postal      VARCHAR(10),
  intro            TEXT,
  actif            BOOLEAN DEFAULT TRUE,
  meta_title       VARCHAR(190),
  meta_description VARCHAR(320)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
--  4. RÉALISATIONS  (portfolio avant / après)
-- =====================================================================
DROP TABLE IF EXISTS realisations;
CREATE TABLE realisations (
  id           INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  slug         VARCHAR(150) NOT NULL UNIQUE,
  titre        VARCHAR(150) NOT NULL,
  commune      VARCHAR(80),
  type_presta  VARCHAR(80),
  description  TEXT,
  duree        VARCHAR(60),
  photo_avant  VARCHAR(255),
  photo_apres  VARCHAR(255),
  couverture   VARCHAR(255),
  publie       BOOLEAN DEFAULT TRUE,
  cree_le      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
--  5. GALERIES & PHOTOS
-- =====================================================================
DROP TABLE IF EXISTS galerie_photos;
DROP TABLE IF EXISTS galeries;
CREATE TABLE galeries (
  id          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  titre       VARCHAR(150) NOT NULL,
  slug        VARCHAR(150) NOT NULL UNIQUE,
  description TEXT,
  couverture  VARCHAR(255),
  publie      BOOLEAN DEFAULT TRUE,
  cree_le     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE galerie_photos (
  id          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  galerie_id  INT UNSIGNED NOT NULL,
  url         VARCHAR(255) NOT NULL,
  alt         VARCHAR(190),
  ordre       SMALLINT UNSIGNED DEFAULT 0,
  FOREIGN KEY (galerie_id) REFERENCES galeries(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
--  6. TÉMOIGNAGES
-- =====================================================================
DROP TABLE IF EXISTS temoignages;
CREATE TABLE temoignages (
  id       INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  auteur   VARCHAR(120) NOT NULL,
  commune  VARCHAR(80),
  note     TINYINT UNSIGNED DEFAULT 5,
  texte    TEXT NOT NULL,
  publie   BOOLEAN DEFAULT TRUE,
  ordre    SMALLINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
--  7. BLOG
-- =====================================================================
DROP TABLE IF EXISTS articles;
CREATE TABLE articles (
  id               INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  slug             VARCHAR(190) NOT NULL UNIQUE,
  titre            VARCHAR(190) NOT NULL,
  extrait          VARCHAR(320),
  contenu          MEDIUMTEXT,
  image_une        VARCHAR(255),
  categorie        VARCHAR(80),
  statut           ENUM('brouillon','publie') DEFAULT 'brouillon',
  meta_title       VARCHAR(190),
  meta_description VARCHAR(320),
  publie_le        DATETIME,
  cree_le          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FULLTEXT ft_articles (titre, contenu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
--  8. ADMINISTRATEURS
-- =====================================================================
DROP TABLE IF EXISTS admins;
CREATE TABLE admins (
  id           INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  nom          VARCHAR(120) NOT NULL,
  email        VARCHAR(190) NOT NULL UNIQUE,
  mot_de_passe VARCHAR(255) NOT NULL,           -- hash argon2/bcrypt
  role         ENUM('super_admin','editeur') DEFAULT 'editeur',
  cree_le      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
--  9. RÉGLAGES GLOBAUX (clé/valeur) — pilote la bulle WhatsApp, etc.
-- =====================================================================
DROP TABLE IF EXISTS reglages;
CREATE TABLE reglages (
  cle    VARCHAR(80) PRIMARY KEY,
  valeur TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
--  DONNÉES DE DÉPART
-- =====================================================================

-- ---- Réglages (dont bulle WhatsApp) ----
INSERT INTO reglages (cle, valeur) VALUES
 ('whatsapp_actif',   '1'),
 ('whatsapp_numero',  '32491644913'),
 ('whatsapp_message', 'Bonjour, je souhaite un devis pour un débarras à Bruxelles'),
 ('whatsapp_infobulle','Un devis rapide ?'),
 ('whatsapp_horaires','Lun–Sam 9h–18h'),
 ('telephone_public', '+32 491 64 49 13'),
 ('email_devis',      'devis@videsgrenier.be'),
 ('site_titre',       'Videsgrenier.be'),
 ('ga_id',            'G-XXXXXXX');

-- ---- Services ----
INSERT INTO services (slug, titre, icone, extrait, ordre) VALUES
 ('vide-maison',           'Vide-maison complète',    '🏠', 'Débarras intégral d''une maison ou d''un appartement.', 1),
 ('debarras-appartement',  'Débarras d''appartement', '📦', 'Avant une vente, une location ou un déménagement.',     2),
 ('cave-grenier',          'Cave, grenier & garage',  '🗝️', 'On vide les espaces encombrés et évacue les encombrants.',3),
 ('rachat-meubles',        'Rachat & brocante',       '💶', 'Objets et meubles rachetés, déduits de votre devis.',   4),
 ('succession',            'Succession & héritage',   '⚖️', 'Accompagnement discret pour vider un logement.',        5),
 ('nettoyage',             'Nettoyage après débarras','🧹', 'Logement rendu vide, balayé et propre.',                6);

-- ---- 19 communes de Bruxelles-Capitale ----
INSERT INTO communes (nom, slug, code_postal) VALUES
 ('Anderlecht','anderlecht','1070'),
 ('Auderghem','auderghem','1160'),
 ('Berchem-Sainte-Agathe','berchem-sainte-agathe','1082'),
 ('Bruxelles-Ville','bruxelles-ville','1000'),
 ('Etterbeek','etterbeek','1040'),
 ('Evere','evere','1140'),
 ('Forest','forest','1190'),
 ('Ganshoren','ganshoren','1083'),
 ('Ixelles','ixelles','1050'),
 ('Jette','jette','1090'),
 ('Koekelberg','koekelberg','1081'),
 ('Molenbeek-Saint-Jean','molenbeek-saint-jean','1080'),
 ('Saint-Gilles','saint-gilles','1060'),
 ('Saint-Josse-ten-Noode','saint-josse-ten-noode','1210'),
 ('Schaerbeek','schaerbeek','1030'),
 ('Uccle','uccle','1180'),
 ('Watermael-Boitsfort','watermael-boitsfort','1170'),
 ('Woluwe-Saint-Lambert','woluwe-saint-lambert','1200'),
 ('Woluwe-Saint-Pierre','woluwe-saint-pierre','1150');

-- ---- Témoignages ----
INSERT INTO temoignages (auteur, commune, note, texte, ordre) VALUES
 ('Sophie D.','Ixelles',5,'Appartement vidé en une journée, propre et impeccable. Le rachat des meubles a bien réduit la note.',1),
 ('Marc L.','Uccle',5,'Très à l''écoute pour la succession de ma mère. Discrets et respectueux, je recommande.',2),
 ('Nadia B.','Schaerbeek',5,'Devis clair par WhatsApp, intervention le lendemain. Efficace et professionnel.',3);

-- ---- Réalisations (exemples) ----
INSERT INTO realisations (slug, titre, commune, type_presta, description, duree) VALUES
 ('appartement-2ch-ixelles','Appartement 2 ch. — Ixelles','Ixelles','Débarras appartement','Débarras complet avec nettoyage final.','1 journée'),
 ('maison-famille-uccle','Maison de famille — Uccle','Uccle','Succession','Succession avec rachat de mobilier ancien.','2 jours'),
 ('cave-grenier-schaerbeek','Cave & grenier — Schaerbeek','Schaerbeek','Cave / grenier','Évacuation d''encombrants dans des espaces difficiles d''accès.','1/2 journée');

-- ---- Compte admin de départ (⚠ remplacer le hash) ----
-- Le hash ci-dessous correspond à « ChangeMoi123! » — À RÉGÉNÉRER en production.
INSERT INTO admins (nom, email, mot_de_passe, role) VALUES
 ('Yassinos','admin@videsgrenier.be','$2y$10$REMPLACER_PAR_UN_VRAI_HASH_BCRYPT','super_admin');

-- =====================================================================
--  FIN
-- =====================================================================
