<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * 3 articles de blog ciblant des recherches à forte intention
 * (prix, succession, vente immobilière) pour attirer du trafic
 * organique et le convertir vers /devis. Contenu HTML : rendu tel
 * quel par resources/views/pages/article.blade.php (voir le
 * commentaire de sécurité dans ce fichier).
 */
class ArticlesSeeder extends Seeder
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
        $ixelles = route('commune.show', 'ixelles');
        $uccle = route('commune.show', 'uccle');
        $succession = route('services.show', 'succession');
        $realisations = route('realisations.index');

        return [

            'prix-debarras-bruxelles' => [
                'titre' => "Prix d'un débarras à Bruxelles : combien ça coûte réellement en 2026",
                'extrait' => "Entre 300 € pour une cave et 1 500 € pour une maison complète : ce qui fait varier le prix d'un débarras à Bruxelles, avec des exemples concrets.",
                'categorie' => 'Prix & devis',
                'statut' => 'publie',
                'publie_le' => Carbon::now()->subDays(2),
                'meta_title' => "Prix d'un débarras à Bruxelles en 2026 — tarifs réels",
                'meta_description' => "Combien coûte un débarras à Bruxelles ? Fourchettes de prix par type de logement, facteurs qui font varier la facture, et comment le rachat la réduit.",
                'contenu' => <<<HTML
<p><strong>Un débarras à Bruxelles coûte généralement entre 300&nbsp;€ pour une cave ou un garage et 1&nbsp;500&nbsp;€ pour une maison complète.</strong> L'écart est large parce que le prix dépend directement du volume à évacuer, de l'accès au logement et de la part d'objets rachetables qui vient en déduction. Voici comment ce prix se construit concrètement, et comment obtenir une estimation qui ne bouge pas le jour de l'intervention.</p>

<h2>Les facteurs qui font varier le prix d'un débarras</h2>
<p>Quatre éléments déterminent l'essentiel de la facture :</p>
<ul>
<li><strong>Le volume</strong> — un studio se débarrasse en quelques heures, une maison sur trois niveaux avec cave et grenier demande une journée complète, parfois deux.</li>
<li><strong>L'accès</strong> — un rez-de-chaussée avec stationnement facile coûte moins cher à débarrasser qu'un quatrième étage sans ascenseur dans une rue où le camion ne peut pas se garer devant l'immeuble.</li>
<li><strong>La nature des objets</strong> — de l'encombrant standard (meubles, cartons, électroménager) se trie vite ; des gravats, un piano ou un coffre-fort demandent une organisation différente.</li>
<li><strong>Le rachat possible</strong> — plus il y a de mobilier ancien, d'objets de collection ou d'électroménager en état, plus la déduction sur la facture finale est importante.</li>
</ul>

<h2>Exemples de prix par type de logement</h2>
<p>Ces fourchettes correspondent à des chantiers réels, avant déduction du rachat :</p>
<ul>
<li><strong>Cave, grenier ou garage seul</strong> : 300 € à 500 €</li>
<li><strong>Appartement une chambre</strong> : 500 € à 800 €</li>
<li><strong>Appartement deux à trois chambres</strong> : 800 € à 1 200 €</li>
<li><strong>Maison complète avec cave et grenier</strong> : 1 200 € à 1 500 €, parfois plus selon le nombre de niveaux</li>
</ul>
<p>Ces montants varient aussi selon la commune : un appartement du centre de <a href="{$ixelles}">Ixelles</a> aux escaliers étroits demande plus de temps de portage qu'une maison avec accès direct à <a href="{$uccle}">Uccle</a>. Voir des exemples de chantiers réalisés dans nos <a href="{$realisations}">réalisations</a>.</p>

<h2>Comment le rachat de vos objets réduit la facture</h2>
<p>Le rachat n'est pas un service séparé : il est déduit directement du prix du débarras. Meubles anciens, vaisselle, livres, vinyles ou objets de brocante sont estimés lors de la visite, et le montant vient en déduction du devis. Sur les chantiers où le mobilier est de qualité — successions, maisons occupées depuis plusieurs décennies — cette déduction réduit souvent la facture de 20 à 50 %.</p>

<h2>Comment obtenir un devis juste</h2>
<p>Un forfait annoncé au téléphone sans avoir vu le logement est rarement fiable. Pour un prix qui ne bouge pas le jour J, une visite ou des photos détaillées permettent d'estimer le volume réel et les objets rachetables. Le devis est ensuite transmis sous 24h, sans engagement.</p>
<p><a href="{$devis}"><strong>Demander un devis gratuit pour votre débarras à Bruxelles →</strong></a></p>
HTML,
            ],

            'vider-maison-succession-bruxelles' => [
                'titre' => "Vider la maison d'un parent décédé à Bruxelles : le guide complet",
                'extrait' => "Par où commencer, quoi mettre de côté, comment faire estimer les objets de valeur : le déroulé complet d'un débarras de succession à Bruxelles.",
                'categorie' => 'Succession',
                'statut' => 'publie',
                'publie_le' => Carbon::now()->subDays(6),
                'meta_title' => 'Vider une maison en succession à Bruxelles — guide complet',
                'meta_description' => "Vider la maison d'un proche décédé à Bruxelles : par où commencer, quoi conserver, comment faire racheter les objets de valeur et organiser le débarras.",
                'contenu' => <<<HTML
<p><strong>Vider la maison d'un parent décédé se fait en trois temps : trier ce qui doit être conservé, faire estimer les objets de valeur, puis débarrasser le reste.</strong> C'est un moment difficile, souvent sous contrainte de délai (vente, fin de bail, partage successoral). Ce guide reprend les étapes dans l'ordre, pour ne rien perdre d'important avant l'intervention.</p>

<h2>Par où commencer : trier avant de débarrasser</h2>
<p>Avant toute évacuation, il est important de distinguer trois catégories : ce que la famille garde, ce qui a une valeur marchande à faire estimer, et ce qui part au débarras. Prendre le temps de cette première visite — même rapide — évite les regrets. Un professionnel habitué aux successions peut aussi accompagner ce tri sur place, sans pression de temps.</p>

<h2>Documents et objets à mettre de côté en priorité</h2>
<p>Certains éléments doivent systématiquement être récupérés avant le débarras :</p>
<ul>
<li>Papiers d'identité, contrats, relevés bancaires et documents notariés</li>
<li>Photos, correspondances et objets à valeur sentimentale</li>
<li>Bijoux, argenterie et petits objets de valeur faciles à égarer dans le volume</li>
<li>Clés, télécommandes et documents liés au logement lui-même (si une vente est prévue)</li>
</ul>

<h2>Faire estimer et racheter les objets de valeur</h2>
<p>Meubles anciens, vaisselle, livres, vinyles ou bibelots de collection peuvent être rachetés plutôt que jetés. Cette estimation se fait lors de la même visite que le débarras : le montant du rachat est déduit directement du prix de l'intervention, ce qui réduit sensiblement la facture — souvent de 20 à 50 % sur les successions bien fournies en mobilier ancien.</p>

<h2>Le déroulement d'une intervention de débarras succession</h2>
<p>Une fois le tri familial terminé, l'intervention se déroule en une visite puis un chantier :</p>
<ul>
<li><strong>Visite et devis</strong> — estimation du volume et des objets rachetables, devis transmis sous 24h.</li>
<li><strong>Intervention</strong> — généralement sous 24 à 48h après accord, une demi-journée à deux jours selon la taille du logement.</li>
<li><strong>Tri final</strong> — don aux associations, revente en brocante, évacuation triée du reste en déchetterie agréée.</li>
<li><strong>Logement rendu propre</strong> — balayé, prêt pour une vente ou une remise des clés.</li>
</ul>
<p>Ce type d'accompagnement fait partie de notre service <a href="{$succession}">succession &amp; héritage</a>, disponible dans les 19 communes de Bruxelles.</p>

<h2>Questions fréquentes</h2>
<p><strong>Faut-il être présent pendant le débarras ?</strong> Non, ce n'est pas obligatoire une fois le tri personnel terminé et les objets à conserver récupérés.</p>
<p><strong>Peut-on faire intervenir l'entreprise avant la fin des démarches notariales ?</strong> Oui, à condition que les héritiers concernés soient d'accord sur ce qui part au débarras — un point à clarifier en amont pour éviter tout litige.</p>
<p><a href="{$devis}"><strong>Demander un devis gratuit pour un débarras de succession →</strong></a></p>
HTML,
            ],

            'debarras-avant-vente-immobiliere-bruxelles' => [
                'titre' => 'Débarrasser un bien avant de le vendre : pourquoi ça accélère la vente à Bruxelles',
                'extrait' => "Un bien vidé et propre se visite mieux et se vend plus vite. Comment organiser le débarras avant vente pour ne pas retarder les visites.",
                'categorie' => 'Vente immobilière',
                'statut' => 'publie',
                'publie_le' => Carbon::now()->subDays(10),
                'meta_title' => 'Débarras avant vente immobilière à Bruxelles — pourquoi, comment',
                'meta_description' => 'Pourquoi vider un bien avant de le mettre en vente à Bruxelles aide à vendre plus vite, et comment organiser le débarras sans retarder les visites.',
                'contenu' => <<<HTML
<p><strong>Un bien débarrassé et propre se visite mieux, se photographie mieux et se vend généralement plus vite qu'un logement encombré.</strong> Les acheteurs projettent difficilement leur propre vie dans un intérieur chargé de meubles et d'objets personnels. Voici pourquoi ce point compte autant que le prix affiché, et comment l'organiser sans retarder la mise en vente.</p>

<h2>Pourquoi un bien vide ou débarrassé se vend plus vite</h2>
<p>Trois raisons reviennent systématiquement chez les agents immobiliers bruxellois :</p>
<ul>
<li><strong>Les photos d'annonce</strong> — un espace vide ou rangé se photographie sous un meilleur angle, ce qui augmente le nombre de clics sur l'annonce.</li>
<li><strong>La perception du volume</strong> — une pièce encombrée paraît plus petite qu'elle ne l'est réellement ; vidée, sa surface réelle devient visible.</li>
<li><strong>La projection de l'acheteur</strong> — il est plus facile d'imaginer son propre mobilier dans un espace neutre que dans un intérieur chargé du quotidien du vendeur.</li>
</ul>

<h2>Home staging ou débarras complet : quelle différence</h2>
<p>Le home staging réorganise et allège un intérieur habité pour le rendre attractif en photo, en gardant l'essentiel du mobilier. Le débarras avant vente va plus loin : il vide totalement le bien, ce qui est la solution la plus courante pour une succession, un bien resté vacant, ou un déménagement déjà effectué. Les deux approches se complètent : certaines agences font intervenir un home stager après un débarras complet pour meubler à minima avant les photos.</p>

<h2>Ce qu'il faut prévoir avant les visites</h2>
<p>Pour que le bien soit prêt à visiter dès la mise en ligne de l'annonce, quelques points à anticiper :</p>
<ul>
<li>Vider entièrement le logement, cave et grenier compris — un espace de rangement encombré inquiète autant qu'une pièce à vivre chargée.</li>
<li>Prévoir un nettoyage final après le débarras, pas seulement un enlèvement des objets.</li>
<li>Réparer ou signaler les petits défauts visibles une fois le mobilier retiré (traces au mur, sol abîmé sous un meuble).</li>
<li>Coordonner la date d'intervention avec celle des photos professionnelles, pour ne pas payer un photographe sur un bien encore encombré.</li>
</ul>

<h2>Le timing idéal avec votre agence immobilière</h2>
<p>Le débarras doit idéalement précéder la prise de photos et la première visite, pas la suivre. Un devis transmis sous 24h et une intervention possible sous 24 à 48h permettent de caler le débarras juste avant la mise en ligne de l'annonce, sans laisser le bien vide et encombré pendant des semaines. Des exemples de biens débarrassés avant mise en vente sont visibles dans nos <a href="{$realisations}">réalisations</a>.</p>
<p><a href="{$devis}"><strong>Demander un devis gratuit avant la mise en vente de votre bien →</strong></a></p>
HTML,
            ],

        ];
    }
}
