<?php

namespace Database\Seeders;

use App\Models\Commune;
use Illuminate\Database\Seeder;

/**
 * Contenu unique par commune (spec §5.1) : chaque page /debarras/{commune} doit
 * se distinguer des 18 autres pour éviter le plafonnement SEO par duplication.
 * Rédigé à partir de la réalité du bâti de chaque commune (quartiers, typologie
 * de logement, contraintes d'accès), pas d'un gabarit générique.
 */
class ContenuCommunesSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->contenus() as $slug => $donnees) {
            Commune::where('slug', $slug)->update($donnees);
        }
    }

    private function contenus(): array
    {
        return [

            'anderlecht' => [
                'intro' => "Anderlecht est la commune la plus étendue de Bruxelles, ce qui veut dire des chantiers très variés : maisons ouvrières mitoyennes du Kuregem et du quartier Wayez, immeubles plus récents près d'Erasme et de la place de la Résistance, mais aussi fermettes et maisons avec jardin du côté de Neerpede et du Scheutbos. Nous intervenons régulièrement pour des successions dans les rangées de maisons du début du XXe siècle, avec leurs caves voûtées et leurs greniers bas de plafond, ainsi que pour des débarras d'appartements dans les tours du quartier Bon-Air. L'accès pose souvent question à Cureghem, où les rues étroites et le stationnement limité demandent d'organiser le camion à l'avance. Nous connaissons aussi bien les étages sans ascenseur des maisons de maître de la chaussée de Mons que les caves communes des immeubles collectifs récents.\n\nQue le chantier tienne en une matinée ou nécessite deux jours pour une maison complète avec dépendances, la formule reste la même : une visite pour estimer le volume, un devis clair sous 24h, puis une intervention où nous trions sur place ce qui part au don, à la revente ou à la déchetterie agréée. Les familles d'Anderlecht qui nous contactent pour une succession apprécient particulièrement que le rachat des meubles anciens, de la vaisselle ou des objets de collection soit déduit directement du prix du débarras plutôt que traité à part.",
                'meta_title' => 'Débarras & vide-maison à Anderlecht (1070) — devis 24h',
                'meta_description' => 'Débarras et vide-maison à Anderlecht : Kuregem, Wayez, Erasme, Scheutbos. Rachat de vos objets déduit du prix, devis gratuit sous 24h.',
            ],

            'auderghem' => [
                'intro' => "Auderghem est une commune verte, en lisière de la forêt de Soignes, où dominent les maisons unifamiliales avec jardin, notamment autour du Vert Chasseur et du quartier Blankedelle. Les chantiers y ressemblent souvent à des vide-maisons complets : garage, jardin avec abri de jardin, grenier aménagé, cave de rangement. Nous intervenons aussi pour des appartements plus récents le long du boulevard du Souverain et près du Parc Seny. Les propriétés avec grand jardin permettent en général un accès camion direct, ce qui accélère l'intervention et limite les frais de portage, un argument que nous répercutons sur le devis. C'est une commune où les successions concernent souvent des maisons occupées par la même famille depuis plusieurs décennies, avec un volume de mobilier et d'objets à trier important.\n\nPour ce type de chantier, nous prenons le temps d'une visite préalable afin d'estimer correctement le volume réparti entre maison, garage et jardin, plutôt que d'annoncer un forfait à l'aveugle. Le mobilier de qualité et les objets anciens fréquemment retrouvés dans ces maisons familiales sont rachetés et déduits du devis, et le logement est rendu balayé et propre en fin d'intervention, prêt pour une mise en vente ou une remise des clés.",
                'meta_title' => 'Débarras & vide-maison à Auderghem (1160) — devis 24h',
                'meta_description' => 'Débarras et vide-maison à Auderghem : Vert Chasseur, Blankedelle, Parc Seny. Rachat déduit du prix, devis gratuit sous 24h.',
            ],

            'berchem-sainte-agathe' => [
                'intro' => "Berchem-Sainte-Agathe est une des plus petites communes de Bruxelles, essentiellement résidentielle, avec des maisons individuelles ou mitoyennes autour de la place Anspach locale et du parc Charles Dierickx. Beaucoup de nos interventions concernent des maisons avec garage et jardin, où le débarras combine cave, grenier et dépendances extérieures. Le tissu y est plus calme qu'au centre de Bruxelles, ce qui facilite le stationnement du camion, mais certaines rues résidentielles restent étroites et demandent une bonne coordination le jour de l'intervention. Nous y accompagnons régulièrement des successions dans des maisons construites entre les années 1930 et 1960, avec du mobilier ancien que nous rachetons volontiers.\n\nLa taille modeste de la commune n'empêche pas des chantiers complets sur deux jours lorsque la maison, le garage et le jardin sont tous à vider en une fois. Nous organisons alors le tri sur place — don aux associations locales, revente en brocante, évacuation triée du reste — et laissons le logement propre et balayé, prêt pour une vente ou une location.",
                'meta_title' => 'Débarras & vide-maison à Berchem-Sainte-Agathe — devis 24h',
                'meta_description' => 'Débarras et vide-maison à Berchem-Sainte-Agathe (1082). Maisons avec jardin et garage. Rachat déduit du prix, devis gratuit sous 24h.',
            ],

            'bruxelles-ville' => [
                'intro' => "La Ville de Bruxelles couvre un territoire très large, du Pentagone historique à Laeken, Neder-over-Heembeek et Haren. Au centre, nous intervenons dans des immeubles anciens aux étages sans ascenseur, avec des escaliers étroits qui demandent un vrai savoir-faire de portage — appartements au-dessus de commerces, maisons de maître transformées en plusieurs logements. À Laeken, le bâti est plus mixte, entre maisons individuelles proches du parc et immeubles plus récents. Les successions dans le centre historique impliquent souvent du mobilier ancien et des objets de collection, que nous estimons sur place et rachetons pour réduire la facture. Nous nous adaptons aussi aux contraintes de circulation et de zones piétonnes du centre-ville, qui demandent d'anticiper les autorisations de stationnement.\n\nÀ Haren et Neder-over-Heembeek, plus excentrés, le bâti redevient plus proche du pavillonnaire avec jardin, ce qui simplifie l'accès camion par rapport au Pentagone. Dans tous les cas, notre organisation reste la même : devis sous 24h, intervention rapide, tri complet sur place et logement rendu propre, avec un accompagnement particulier pour les zones à accès réglementé du centre historique.",
                'meta_title' => 'Débarras & vide-maison à Bruxelles-Ville (1000) — devis 24h',
                'meta_description' => 'Débarras et vide-maison à Bruxelles-Ville : Pentagone, Laeken, Neder-over-Heembeek. Rachat déduit du prix, devis gratuit sous 24h.',
            ],

            'etterbeek' => [
                'intro' => "Etterbeek est une commune dense, à deux pas des institutions européennes, avec un habitat fait d'immeubles de rapport et de maisons de style Art nouveau, notamment autour du Cinquantenaire et des squares. Beaucoup de nos chantiers concernent des appartements loués à des expatriés ou des fonctionnaires européens, avec des rotations locatives fréquentes qui demandent des débarras rapides entre deux baux. Les étages sans ascenseur sont courants dans les immeubles du début du XXe siècle, notamment près de la chaussée de Wavre et du quartier Saint-Michel. Nous intervenons aussi pour des successions dans les maisons bourgeoises plus au sud, vers Boondael, où le mobilier ancien se prête bien au rachat.\n\nLa rotation locative rapide typique d'Etterbeek nous impose souvent des délais serrés entre l'état des lieux de sortie et l'entrée du locataire suivant : nous priorisons ces interventions pour tenir les échéances. Le logement est systématiquement rendu balayé et propre, un point particulièrement suivi par les agences immobilières du quartier européen.",
                'meta_title' => 'Débarras & vide-maison à Etterbeek (1040) — devis 24h',
                'meta_description' => 'Débarras et vide-maison à Etterbeek : Cinquantenaire, Squares, quartier européen. Rachat déduit du prix, devis gratuit sous 24h.',
            ],

            'evere' => [
                'intro' => "Evere combine des quartiers résidentiels calmes, comme les abords du cimetière de Bruxelles et le quartier Paduwa, avec des zones plus denses proches de la chaussée de Haecht. Le bâti mélange maisons individuelles avec jardin et immeubles d'appartements plus récents, notamment vers Chateau Malou. Nous y intervenons souvent pour des débarras de garages et de caves dans les maisons construites dans les années 1950 à 1970, ainsi que pour des appartements dans les résidences plus modernes. La proximité de l'aéroport et des zones militaires n'entraîne pas de contrainte particulière pour nos interventions, qui restent simples à organiser côté accès et stationnement.\n\nCes maisons d'après-guerre recèlent souvent du mobilier des années 1950-1970 que nous rachetons avec intérêt — bahuts, vaisselle, luminaires d'époque — ce qui allège sensiblement la facture finale pour les familles en cours de succession. L'accès facile aux rues d'Evere nous permet en général de proposer une intervention sous 24 à 48h.",
                'meta_title' => 'Débarras & vide-maison à Evere (1140) — devis 24h',
                'meta_description' => 'Débarras et vide-maison à Evere : Paduwa, Chateau Malou, chaussée de Haecht. Rachat déduit du prix, devis gratuit sous 24h.',
            ],

            'forest' => [
                'intro' => "Forest est une commune contrastée, entre le quartier populaire du Bempt et de la chaussée de Bruxelles, et les rues plus cossues proches du parc Duden et de Saint-Denis. Le bâti y est varié : maisons mitoyennes des années 1900 avec cave voûtée, immeubles Art nouveau proches de la frontière avec Saint-Gilles, et pavillons avec jardin vers l'Altitude Cent. Le relief vallonné de Forest signifie parfois des accès en pente qui demandent d'adapter le stationnement du camion. Nous intervenons souvent pour des successions de maisons familiales occupées depuis longtemps, avec du mobilier ancien à racheter, ainsi que pour des débarras d'appartements dans les immeubles de la chaussée de Bruxelles.\n\nLe contraste entre les quartiers de Forest se retrouve dans nos devis : un appartement du Bempt se débarrasse en quelques heures, quand une maison de l'Altitude Cent avec cave, grenier et jardin demande une journée complète. Dans les deux cas, la visite préalable permet d'annoncer un prix juste, rachat des objets de valeur déjà déduit.",
                'meta_title' => 'Débarras & vide-maison à Forest (1190) — devis 24h',
                'meta_description' => 'Débarras et vide-maison à Forest : Bempt, parc Duden, Altitude Cent. Rachat déduit du prix, devis gratuit sous 24h.',
            ],

            'ganshoren' => [
                'intro' => "Ganshoren est une petite commune résidentielle du nord-ouest de Bruxelles, avec des maisons individuelles ou mitoyennes construites principalement entre 1930 et 1970, souvent avec jardin et garage. C'est un profil de chantier proche du vide-maison classique : cave, grenier, garage et dépendances à vider en une seule intervention. Les rues y sont généralement plus larges qu'au centre-ville, ce qui simplifie l'accès pour le camion. Nous y accompagnons régulièrement des successions où plusieurs générations ont accumulé du mobilier, de la vaisselle et des objets de collection, que nous évaluons sur place pour réduire le montant du débarras.\n\nParce que ces maisons ont souvent été habitées par la même famille sur trois ou quatre décennies, le volume à trier dépasse largement ce qu'un débarras d'appartement demande. Nous prévoyons donc systématiquement une journée complète pour ce type de chantier, avec tri sur place entre don, revente et évacuation. Le nettoyage final est inclus, la maison est rendue prête pour une visite ou une remise des clés.",
                'meta_title' => 'Débarras & vide-maison à Ganshoren (1083) — devis 24h',
                'meta_description' => 'Débarras et vide-maison à Ganshoren. Maisons avec jardin et garage. Rachat de vos objets déduit du prix, devis gratuit sous 24h.',
            ],

            'ixelles' => [
                'intro' => "Ixelles est l'une des communes les plus denses de Bruxelles, entre les immeubles Art nouveau du quartier Châtelain, les étangs d'Ixelles, le quartier animé de Matonge et les logements étudiants autour de l'ULB et de Flagey. Beaucoup de nos interventions concernent des appartements aux étages élevés, dans des immeubles sans ascenseur avec des escaliers en colimaçon typiques du début du XXe siècle — un vrai enjeu de portage que nous maîtrisons bien. Les débarras de kots étudiants en fin d'année académique sont fréquents, tout comme les successions dans les maisons de maître du quartier Châtelain, riches en mobilier ancien et en objets de valeur que nous rachetons. Le stationnement y est souvent limité, nous anticipons donc systématiquement une zone de chargement avant l'intervention.\n\nEntre juin et septembre, la demande de débarras de kots explose autour de l'ULB, de la VUB et de la place Flagey : nous adaptons nos équipes pour absorber ces pics saisonniers sans allonger les délais. Pour les successions du quartier Châtelain, la présence fréquente d'objets Art nouveau ou de collection justifie une estimation plus poussée lors de la visite.",
                'meta_title' => 'Débarras & vide-maison à Ixelles (1050) — devis 24h',
                'meta_description' => 'Débarras et vide-appartement à Ixelles : Châtelain, Matonge, Flagey, ULB. Rachat déduit du prix, devis gratuit sous 24h.',
            ],

            'jette' => [
                'intro' => "Jette mélange un centre commerçant animé autour de la place Cardinal Mercier et des quartiers résidentiels avec maisons et jardins, notamment vers le parc Roi Baudouin et le Miroir. Le bâti est assez homogène : maisons mitoyennes des années 1920-1950 avec cave et grenier, complétées par quelques résidences plus récentes près de l'hôpital UZ Brussel. Nous y intervenons souvent pour des successions dans des maisons de famille, avec un débarras complet cave-grenier-garage, ainsi que pour des départs de logements plus modestes en location. L'accès y est généralement simple, les rues résidentielles de Jette permettant un stationnement proche de l'entrée.\n\nLa proximité de l'UZ Brussel génère aussi des débarras liés à des départs en maison de repos ou des réductions de logement : des interventions souvent plus sensibles, où nous prenons le temps d'identifier avec la famille ce qui doit être conservé avant de commencer le tri. Le reste est trié entre don, revente et évacuation, avec le rachat des objets de valeur déduit du devis.",
                'meta_title' => 'Débarras & vide-maison à Jette (1090) — devis 24h',
                'meta_description' => 'Débarras et vide-maison à Jette : centre, parc Roi Baudouin, Miroir. Rachat de vos objets déduit du prix, devis gratuit sous 24h.',
            ],

            'koekelberg' => [
                'intro' => "Koekelberg est la plus petite commune de Belgique par la superficie, très densément bâtie autour de la basilique du Sacré-Cœur. L'habitat y est dominé par des immeubles de rapport et des maisons mitoyennes converties en plusieurs logements, avec des étages sans ascenseur fréquents. Nos interventions concernent surtout des débarras d'appartements en rotation locative rapide, où l'accès étroit aux cages d'escalier demande une bonne organisation du portage. La densité du bâti signifie aussi un stationnement souvent limité aux abords immédiats de la basilique et le long de la chaussée de Jette, que nous anticipons avant chaque chantier.\n\nMalgré sa petite taille, Koekelberg concentre beaucoup de logements loués : nous y intervenons donc fréquemment sur des délais courts entre le départ d'un locataire et l'entrée du suivant, avec un débarras suivi d'un nettoyage complet pour l'état des lieux. Le devis reste gratuit et sans engagement, même pour un simple studio à vider en quelques heures.",
                'meta_title' => 'Débarras & vide-appartement à Koekelberg (1081) — devis 24h',
                'meta_description' => 'Débarras et vide-appartement à Koekelberg, près de la basilique. Rachat de vos objets déduit du prix, devis gratuit sous 24h.',
            ],

            'molenbeek-saint-jean' => [
                'intro' => "Molenbeek-Saint-Jean est une commune étendue, entre les maisons ouvrières mitoyennes proches du canal, les logements sociaux du Karreveld et des quartiers en pleine rénovation urbaine comme le Vieux Molenbeek. Le bâti ancien, avec ses caves voûtées et ses combles bas, cohabite avec des immeubles collectifs plus récents. Nous intervenons régulièrement pour des successions dans les maisons de la chaussée de Gand et pour des débarras d'appartements dans les quartiers en réhabilitation, où l'accès est parfois compliqué par des travaux de voirie en cours. Le rachat des objets de valeur y est particulièrement apprécié, beaucoup de logements conservant du mobilier ancien transmis sur plusieurs générations.\n\nLes chantiers de rénovation en cours dans le Vieux Molenbeek nous amènent souvent à coordonner l'intervention avec l'entrepreneur ou le promoteur, pour libérer le bien avant le début des travaux. Nous restons flexibles sur les horaires pour respecter ce type de calendrier serré, avec un devis transmis sous 24h pour ne pas retarder le planning du chantier.",
                'meta_title' => 'Débarras & vide-maison à Molenbeek-Saint-Jean (1080) — devis',
                'meta_description' => 'Débarras et vide-maison à Molenbeek-Saint-Jean : canal, Karreveld, Vieux Molenbeek. Rachat déduit du prix, devis gratuit sous 24h.',
            ],

            'saint-gilles' => [
                'intro' => "Saint-Gilles est réputée pour ses maisons de maître Art nouveau, en particulier autour de l'avenue Louise et du quartier de la Barrière, mais l'essentiel du bâti reste fait de maisons mitoyennes étroites converties en plusieurs appartements, sans ascenseur, avec des escaliers parfois raides. C'est une des communes où le portage est le plus exigeant, et nous y intervenons très régulièrement pour cette raison. Le Parvis de Saint-Gilles et ses environs concentrent beaucoup de kots et de petits logements, avec des rotations locatives fréquentes. Nous y menons aussi des successions dans les maisons bourgeoises, riches en mobilier ancien et en objets de collection que nous rachetons systématiquement lors de l'estimation.\n\nLes escaliers étroits et les paliers réduits typiques du bâti de Saint-Gilles demandent souvent de démonter certains meubles volumineux pour les sortir : notre équipe est équipée pour ce genre de contrainte, fréquente aussi bien Barrière que du côté du Parvis. Le nettoyage final fait partie de chaque intervention, sans supplément caché sur le devis initial.",
                'meta_title' => 'Débarras & vide-appartement à Saint-Gilles (1060) — devis 24h',
                'meta_description' => 'Débarras et vide-appartement à Saint-Gilles : Barrière, Parvis, avenue Louise. Rachat déduit du prix, devis gratuit sous 24h.',
            ],

            'saint-josse-ten-noode' => [
                'intro' => "Saint-Josse-ten-Noode est la commune la plus densément peuplée de Belgique, avec un bâti fait presque exclusivement de petits appartements dans des immeubles de rapport, autour de la place Madou et de la chaussée de Louvain. Les logements y sont souvent de taille modeste, ce qui donne des débarras plus rapides mais fréquents, notamment lors des rotations locatives. L'accès aux immeubles anciens sans ascenseur, avec des cages d'escalier étroites, demande une organisation précise du portage. Le stationnement autour de la place Madou et de la gare du Nord est limité, nous prévoyons donc toujours une zone de chargement à l'avance pour ne pas perdre de temps sur le chantier.\n\nLa petite taille des logements ne veut pas dire petit prix automatiquement : certains studios très encombrés demandent autant de temps de tri qu'un appartement plus grand mais mieux rangé. C'est pourquoi nous préférons toujours une estimation sur place plutôt qu'un tarif au mètre carré.",
                'meta_title' => 'Débarras & vide-appartement à Saint-Josse-ten-Noode — devis',
                'meta_description' => 'Débarras et vide-appartement à Saint-Josse-ten-Noode (1210), place Madou. Rachat déduit du prix, devis gratuit sous 24h.',
            ],

            'schaerbeek' => [
                'intro' => "Schaerbeek est l'une des plus grandes communes de Bruxelles, avec un bâti très varié : maisons Art nouveau et Art déco autour du square Riga et du parc Josaphat, immeubles de rapport plus denses vers la chaussée de Haecht, et quartiers résidentiels plus calmes du côté de Terdelt et de Helmet. Nous y intervenons pour des successions dans de grandes maisons bourgeoises, avec plusieurs étages et un volume de mobilier important, ainsi que pour des débarras d'appartements dans les immeubles plus modestes du bas de la commune. L'accès varie beaucoup selon les quartiers : facile près du parc Josaphat, plus contraint dans les rues denses proches de la gare du Nord.\n\nLes maisons bourgeoises de Schaerbeek, souvent sur trois ou quatre niveaux, contiennent fréquemment un mélange de mobilier de plusieurs époques accumulé sur des décennies. Nous prenons le temps d'identifier les pièces rachetables — meubles, luminaires, vaisselle, livres — avant de passer au tri du reste.",
                'meta_title' => 'Débarras & vide-maison à Schaerbeek (1030) — devis 24h',
                'meta_description' => 'Débarras et vide-maison à Schaerbeek : Josaphat, Terdelt, Helmet. Rachat de vos objets déduit du prix, devis gratuit sous 24h.',
            ],

            'uccle' => [
                'intro' => "Uccle est l'une des communes les plus étendues et les plus vertes de Bruxelles, avec de grandes maisons individuelles et des villas dotées de jardins spacieux, notamment autour du Fort Jaco, de Saint-Job et du Vivier d'Oie. Nos interventions y prennent souvent la forme de vide-maisons complets : plusieurs étages, garage, jardin avec dépendances, cave et grenier aménagés. Les successions y concernent fréquemment des propriétés familiales occupées depuis des décennies, avec un mobilier ancien de qualité que nous estimons et rachetons volontiers, ce qui réduit sensiblement la facture finale. L'accès est généralement aisé grâce aux rues résidentielles larges, sauf dans certains quartiers plus anciens proches du Dieweg où les voiries sont plus étroites.\n\nLa taille de ces propriétés — parfois plus de 300 m² habitables avec dépendances — justifie presque toujours une visite préalable détaillée. C'est là que nous identifions les pièces à fort potentiel de rachat, meubles de style, argenterie, tableaux ou objets de collection, avant de chiffrer le débarras complet.",
                'meta_title' => 'Débarras & vide-maison à Uccle (1180) — devis 24h',
                'meta_description' => 'Débarras et vide-maison à Uccle : Fort Jaco, Saint-Job, Vivier d’Oie. Rachat de vos objets déduit du prix, devis gratuit sous 24h.',
            ],

            'watermael-boitsfort' => [
                'intro' => "Watermael-Boitsfort est la commune la plus boisée de Bruxelles, en bordure de la forêt de Soignes, avec un habitat fait de maisons individuelles avec jardin, notamment dans le quartier du Logis-Floréal, cité-jardin classée, et autour de l'étang de Boitsfort. Nos chantiers y ressemblent souvent à des vide-maisons complets, avec cave, grenier, garage et abri de jardin. Les propriétés du Logis-Floréal, plus anciennes, demandent parfois une attention particulière pour préserver les boiseries et les aménagements d'origine pendant le débarras. L'accès est généralement facile, les rues résidentielles étant larges et peu encombrées, ce qui permet un stationnement proche de l'entrée pour la majorité des interventions.\n\nLe classement patrimonial du Logis-Floréal impose parfois des précautions particulières lors du débarras, notamment pour ne pas endommager boiseries et huisseries d'origine : nous en tenons compte dans l'organisation du chantier et le choix du matériel de portage. Le calme de la commune permet aussi d'étaler l'intervention sur plusieurs demi-journées si la famille préfère trier progressivement.",
                'meta_title' => 'Débarras & vide-maison à Watermael-Boitsfort (1170) — devis',
                'meta_description' => 'Débarras et vide-maison à Watermael-Boitsfort : Logis-Floréal, étang de Boitsfort. Rachat déduit du prix, devis gratuit sous 24h.',
            ],

            'woluwe-saint-lambert' => [
                'intro' => "Woluwe-Saint-Lambert est une commune résidentielle à l'est de Bruxelles, entre les maisons avec jardin du quartier des Squares et les immeubles d'appartements plus récents proches du Woluwe Shopping Center et du campus universitaire de la VUB/ULB. Nous y intervenons pour des successions dans des maisons familiales spacieuses, avec un débarras couvrant plusieurs étages, cave et garage, ainsi que pour des débarras d'appartements dans les résidences plus modernes. L'accès est en général simple, les rues résidentielles permettant un stationnement proche de l'entrée, sauf aux abords immédiats du centre commercial où la circulation est plus dense en journée.\n\nLa proximité du campus universitaire génère aussi une demande régulière de débarras de petits logements étudiants, en plus des successions dans les maisons familiales du quartier des Squares. Nous adaptons le format de l'intervention — équipe réduite pour un studio, équipe complète pour une maison — à chaque situation, avec dans tous les cas un devis gratuit sous 24h.",
                'meta_title' => 'Débarras & vide-maison à Woluwe-Saint-Lambert (1200) — devis',
                'meta_description' => 'Débarras et vide-maison à Woluwe-Saint-Lambert : Squares, Woluwe Shopping. Rachat déduit du prix, devis gratuit sous 24h.',
            ],

            'woluwe-saint-pierre' => [
                'intro' => "Woluwe-Saint-Pierre est une commune résidentielle huppée, avec de grandes maisons et villas dotées de jardins, notamment autour du parc de Woluwe et du quartier Stockel. Nos interventions y prennent souvent la forme de vide-maisons complets, avec plusieurs étages, garage et dépendances extérieures à débarrasser. Les successions y concernent fréquemment des propriétés familiales de longue date, avec un mobilier de qualité, de la vaisselle ancienne et parfois des objets de collection que nous rachetons pour alléger la facture. L'accès est généralement excellent grâce aux rues résidentielles larges du quartier Stockel et des abords du parc de Woluwe.\n\nLe standing des propriétés de Woluwe-Saint-Pierre nous amène régulièrement à faire appel à notre réseau de brocanteurs spécialisés pour l'estimation d'objets de valeur — mobilier de style, argenterie, tableaux — avant de chiffrer le débarras complet du bien. Le logement est toujours rendu vide, balayé et propre en fin de chantier, un point d'attention particulier pour les biens mis en vente dans ce quartier recherché.",
                'meta_title' => 'Débarras & vide-maison à Woluwe-Saint-Pierre (1150) — devis',
                'meta_description' => 'Débarras et vide-maison à Woluwe-Saint-Pierre : parc de Woluwe, Stockel. Rachat de vos objets déduit du prix, devis gratuit sous 24h.',
            ],

        ];
    }
}
