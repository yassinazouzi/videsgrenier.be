<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * 3 articles complémentaires aux 3 déjà publiés (voir ArticlesSeeder), centrés
 * sur l'intention de recherche "prix le plus bas" / "gratuit" / "comparatif" —
 * les requêtes à plus fort volume sur ce secteur. Ton volontairement honnête :
 * un contenu qui ne fait que vanter le "pas cher" sans nuance se fait sanctionner
 * par Google (E-E-A-T) et ne convertit pas — on donne de vrais leviers ET on
 * prévient des fausses économies.
 */
class ArticlesPrixSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->articles() as $slug => $donnees) {
            Article::updateOrCreate(['slug' => $slug], $donnees);
        }
    }

    private function articles(): array
    {
        $devis = route('devis.form');
        $rachat = route('services.show', 'rachat-meubles');
        $prixArticle = route('blog.show', 'prix-debarras-bruxelles');

        return [

            'debarras-pas-cher-bruxelles' => [
                'titre' => 'Débarras pas cher à Bruxelles : les leviers qui marchent vraiment',
                'extrait' => "Trier en amont, maximiser le rachat, choisir le bon créneau : ce qui fait vraiment baisser le prix d'un débarras à Bruxelles, et les fausses économies à éviter.",
                'categorie' => 'Prix & devis',
                'statut' => 'publie',
                'publie_le' => Carbon::now()->subDays(15),
                'meta_title' => 'Débarras pas cher à Bruxelles : ce qui marche vraiment',
                'meta_description' => "Comment réduire le prix d'un débarras à Bruxelles sans mauvaise surprise : rachat, tri en amont, accès facile, et les pièges des offres trop alléchantes.",
                'contenu' => <<<HTML
<p><strong>Un débarras pas cher, ce n'est pas un tarif cassé sorti de nulle part : c'est la somme de plusieurs leviers concrets qui réduisent le volume facturé et augmentent le montant racheté.</strong> Certains fonctionnent vraiment, d'autres ne sont que des économies apparentes qui coûtent plus cher au final. Voici lesquels utiliser, et lesquels éviter.</p>

<h2>Les leviers qui font vraiment baisser le prix</h2>
<ul>
<li><strong>Maximiser le rachat</strong> — meubles anciens, vaisselle, livres, vinyles, électroménager en état : plus il y a d'objets rachetables, plus la déduction sur le devis est importante. Ne rien jeter avant la visite d'estimation.</li>
<li><strong>Trier l'essentiel en amont</strong> — récupérer soi-même les objets personnels, papiers et souvenirs avant l'intervention réduit le volume à traiter, donc le temps de main-d'œuvre facturé.</li>
<li><strong>Un accès facile</strong> — rez-de-chaussée, ascenseur fonctionnel, stationnement possible devant l'immeuble : chaque contrainte d'accès (étage élevé, rue étroite, portage long) ajoute du temps, donc du coût.</li>
<li><strong>Une seule intervention groupée</strong> — combiner débarras et nettoyage final en une seule visite revient moins cher que faire appel à deux prestataires séparés.</li>
<li><strong>La flexibilité sur la date</strong> — un créneau en semaine plutôt qu'un samedi, quand c'est possible, laisse plus de marge de planification et donc de négociation.</li>
</ul>

<h2>Les fausses économies à éviter</h2>
<p>Certaines offres "pas chères" coûtent en réalité plus cher, d'une manière ou d'une autre :</p>
<ul>
<li><strong>Les prestataires informels sans assurance ni agrément</strong> — en cas de dégât pendant l'intervention (mur, sol, ascenseur), aucun recours possible. Et un dépôt non conforme en déchetterie agréée expose le propriétaire du logement à une amende, même si c'est l'entreprise qui a mal trié.</li>
<li><strong>Un devis "à l'heure" sans visite préalable</strong> — sans estimation du volume réel, l'intervention peut s'éterniser et finir plus chère qu'un forfait clair annoncé à l'avance.</li>
<li><strong>Le faire soi-même sans compter le temps</strong> — location de camionnette, plusieurs allers-retours en déchetterie, journées de congé : le calcul complet dépasse souvent le prix d'un débarras professionnel, surtout pour un volume important. Voir notre <a href="{$prixArticle}">comparatif détaillé des prix</a>.</li>
</ul>

<h2>Notre politique de prix</h2>
<p>Le devis est établi après une visite ou des photos détaillées, jamais à l'aveugle. Le <a href="{$rachat}">rachat de vos objets</a> est déduit directement du montant, sans démarche séparée. Aucun frais caché n'apparaît le jour de l'intervention : le prix annoncé est le prix final.</p>
<p><a href="{$devis}"><strong>Demander un devis gratuit et sans engagement →</strong></a></p>
HTML,
            ],

            'debarras-gratuit-bruxelles' => [
                'titre' => 'Débarras gratuit à Bruxelles : mythe ou réalité ?',
                'extrait' => "Un débarras 100% gratuit existe, mais seulement dans un cas précis. Ce qu'il faut savoir avant de répondre à une offre \"gratuite\" à Bruxelles.",
                'categorie' => 'Prix & devis',
                'statut' => 'publie',
                'publie_le' => Carbon::now()->subDays(19),
                'meta_title' => 'Débarras gratuit à Bruxelles : mythe ou réalité ?',
                'meta_description' => 'Un débarras vraiment gratuit à Bruxelles, ça existe dans quel cas ? Ce que cachent souvent les offres "100% gratuites" et comment vérifier avant de signer.',
                'contenu' => <<<HTML
<p><strong>Un débarras réellement gratuit existe, mais seulement quand la valeur de revente du contenu couvre entièrement le coût de l'intervention.</strong> C'est plus rare qu'annoncé : la plupart des logements contiennent un mélange d'objets de valeur et d'encombrants sans valeur marchande, ce qui laisse un solde à payer même après déduction du rachat.</p>

<h2>Dans quels cas un débarras peut être (quasi) gratuit</h2>
<p>Le rachat couvre — ou dépasse — le coût du débarras dans des situations précises :</p>
<ul>
<li>Un logement meublé avec du mobilier ancien de qualité, en quantité importante</li>
<li>Une collection (vinyles, livres anciens, argenterie, bibelots) à forte valeur cumulée</li>
<li>Un volume réduit à évacuer (peu d'encombrants sans valeur) combiné à un contenu qui, lui, en a</li>
</ul>
<p>Dans ces cas, le montant racheté peut effectivement couvrir tout ou partie du débarras. C'est une estimation qui se fait au cas par cas, jamais promise à l'avance sans avoir vu le logement.</p>

<h2>Pourquoi la plupart des offres "100% gratuites" méritent d'être vérifiées</h2>
<ul>
<li><strong>Sélection partielle du contenu</strong> — certains "débarrasseurs gratuits" ne prennent que les objets de valeur et laissent le reste (encombrants, déchets) à la charge du propriétaire, qui doit alors payer une deuxième intervention pour finir le travail.</li>
<li><strong>Absence d'agrément</strong> — sans assurance ni accès aux filières de tri agréées, aucun recours n'est possible en cas de dégât, et le propriétaire du logement reste responsable d'un éventuel dépôt non conforme.</li>
<li><strong>Frais annoncés après coup</strong> — transport, tri, "frais de dossier" : le gratuit affiché ne l'est parfois plus une fois la facture détaillée.</li>
</ul>

<h2>Comment se rapprocher au maximum du gratuit, honnêtement</h2>
<p>Chez nous, l'estimation du rachat se fait objectivement lors de la visite, et le montant est déduit intégralement et de manière transparente du devis — jamais après coup. Si le contenu du logement couvre tout le coût de l'intervention, le débarras revient effectivement à 0 €. Si ce n'est pas le cas, le solde est annoncé clairement avant toute intervention, sans surprise.</p>

<h2>Questions fréquentes</h2>
<p><strong>Le rachat est-il garanti à l'avance ?</strong> Non, il s'estime lors de la visite. Un logement encombré mais sans objets de valeur ne permettra pas un débarras gratuit, quel que soit le prestataire.</p>
<p><strong>Peut-on refuser l'intervention si le devis ne convient pas ?</strong> Oui, le devis est gratuit et sans engagement — aucune obligation d'accepter.</p>
<p><a href="{$devis}"><strong>Demander une estimation gratuite de rachat →</strong></a></p>
HTML,
            ],

            'comparatif-prix-debarras-diy-entreprise-bruxelles' => [
                'titre' => 'Vider une maison au meilleur prix : le vrai comparatif entre le faire soi-même et une entreprise',
                'extrait' => "Location de camionnette, trajets en déchetterie, temps perdu : le calcul complet du débarras \"soi-même\" comparé à une entreprise professionnelle à Bruxelles.",
                'categorie' => 'Prix & devis',
                'statut' => 'publie',
                'publie_le' => Carbon::now()->subDays(23),
                'meta_title' => 'Débarras DIY ou entreprise : comparatif prix Bruxelles',
                'meta_description' => "Vider une maison soi-même ou passer par une entreprise : comparatif honnête des coûts réels à Bruxelles, camionnette, déchetterie, temps et rachat inclus.",
                'contenu' => <<<HTML
<p><strong>Sur le papier, débarrasser un logement soi-même paraît toujours moins cher qu'une entreprise. En comptant tout — camionnette, déchetterie, temps, absence de rachat — l'écart réel est souvent bien plus faible qu'on ne le pense, surtout pour un volume important.</strong> Voici le calcul complet, honnêtement, des deux côtés.</p>

<h2>Le calcul du "tout seul" : ce qu'on oublie souvent</h2>
<ul>
<li><strong>Location d'une camionnette</strong> — comptez une journée de location, plus le carburant, pour un aller-retour vers un recypark bruxellois. Un gros volume demande souvent plusieurs trajets.</li>
<li><strong>Frais de déchetterie</strong> — les recyparks bruxellois appliquent une facturation au-delà d'un certain volume gratuit ; les encombrants en grande quantité ou les gravats sortent vite de ce seuil.</li>
<li><strong>Le temps</strong> — porter, charger, décharger, refaire la route plusieurs fois : c'est souvent une à deux journées complètes, parfois posées en congé.</li>
<li><strong>Aucun rachat</strong> — tout part au même endroit, sans distinction entre ce qui a de la valeur et ce qui n'en a pas. Un meuble ancien qui aurait pu être racheté finit à la benne comme le reste.</li>
<li><strong>Le risque physique</strong> — meubles lourds, escaliers, objets encombrants : le risque de blessure n'est pas négligeable, surtout sans matériel de portage adapté.</li>
</ul>

<h2>Le calcul "entreprise professionnelle"</h2>
<p>Le prix annoncé par une entreprise de débarras couvre en une seule fois la main-d'œuvre, le transport, le tri, l'évacuation en filières agréées et le nettoyage final. Le rachat des objets de valeur est déduit directement, ce qui réduit d'autant la facture. Aucune contrainte de logistique côté client : ni camionnette à réserver, ni trajet à organiser, ni journée de congé à poser.</p>

<h2>Ce que ça donne en pratique</h2>
<p>Pour un volume comparable (par exemple un appartement deux chambres), le coût réel du "tout seul" — camionnette, carburant, frais de déchetterie, valeur perdue du mobilier non racheté — se rapproche souvent de celui d'un débarras professionnel une fois tout additionné, sans compter le temps personnel investi. Le détail des fourchettes de prix par type de logement est disponible dans notre <a href="{$prixArticle}">article sur les prix d'un débarras à Bruxelles</a>.</p>

<h2>Quand le "tout seul" reste le bon choix</h2>
<p>Pour rester honnête : débarrasser soi-même garde du sens dans certains cas précis — un très petit volume (une cave légère, quelques cartons), du temps disponible sans contrainte, et un accès simple au véhicule et à la déchetterie. Pour un logement complet, une succession, ou un accès compliqué (étages, stationnement difficile), une entreprise reste généralement le choix le plus rationnel une fois tous les coûts additionnés.</p>
<p><a href="{$devis}"><strong>Comparer avec un devis gratuit et sans engagement →</strong></a></p>
HTML,
            ],

        ];
    }
}
