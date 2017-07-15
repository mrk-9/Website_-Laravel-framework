<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

abstract class BaseSeeder extends Seeder
{
    protected $faker;
    protected $company_types = array("SARL", "EURL", "SA");
    protected $status = array("pending", "valid");
    protected $type = array("agency", "advertiser");
    protected $supports = array("Affichage", "Presse");
    protected $categories = array(
        "Presse Quotidienne Nationale",
        "Presse Quotidienne Régionale",
        "Presse du 7ème Jour",
        "Presse Magazine",
        "Presse Hebdomadaire Régionale",
        "Presse Professionnelle",
        "Presse étrangère",
        "Annuaires et Guides",
        "Magazine de Marque",
        "Presse Gratuite d'Annonces",
        "Presse Gratuite d'Information",
        "Presse Gratuite d'Information à Diffusion Certifiée"
    );

    protected $broadcasting_areas = array(
        "Ile-de-France",
        "Nord-Est",
        "Nord-Ouest",
        "Sud-Est",
        "Sud-Ouest"
    );

    protected $targets = array(
        "Artisans, Commerçants, Dirigeants et Cadres des TPE (moins de 10 salariés)",
        "Dirigeants et Cadres des PME (10 à 500 salariés)",
        "Enfants",
        "Femmes 18/34 ans",
        "Femmes 35/49 ans",
        "Femmes 50 ans et plus",
        "Hommes 18/34 ans",
        "Hommes 35 à 49 ans",
        "Hommes 50 et plus",
        "Responsables des Achats",
        "Ouvriers",
        "Professions intermédiaires",
        "Professions libérales, Conseils",
        "Retraités",
        "Sportifs",
        "Autres"
    );

    protected $themes = array(
        "Annuaire / Guide",
        "Magazine 4x4",
        "Magazine Actualité",
        "Magazine Adolescents",
        "Magazine Aéronautique",
        "Magazine Animaux",
        "Magazine Antiquités Brocantes Enchères",
        "Magazine Architecture",
        "Magazine Arts et Design",
        "Magazine Automobile",
        "Magazine Bateaux",
        "Magazine BD (Bande Dessinée)",
        "Magazine Bien Etre",
        "Magazine Bio",
        "Magazine Bricolage",
        "Magazine de Charmes",
        "Magazine Chasse Pêche",
        "Magazine Cheval",
        "Magazine Cinéma - Vidéo",
        "Magazine Consommation",
        "Magazine Cuisine et Gastronomie",
        "Magazine Culture et Tendance",
        "Magazine sur les Drones",
        "Magazine Economie",
        "Magazine Electronique",
        "Magazine Enfants",
        "Magazine Emploi",
        "Magazine Environnement Ecologie",
        "Magazine Feminin",
        "Magazine Finance",
        "Magazine Gay et Lesbien",
        "Magazine Géopolitique Défense",
        "Magazine Histoire",
        "Magazine Handicap",
        "Magazine Homme",
        "Magazine Horoscope Voyance",
        "Magazine Immobilier",
        "Magazine Informatique",
        "Magazine Japonais",
        "Magazine Jardin",
        "Magazine Jeux",
        "Magazine Jeux Vidéo",
        "Magazine Lifestyle",
        "Magazine Littéraire",
        "Magazine Mariage",
        "Magazine Maison Déco",
        "Magazine Maison Bois",
        "Magazine Militaire",
        "Magazine Mobile",
        "Magazine Mode",
        "Magazine Modélisme",
        "Magazine Moto",
        "Magazine Musique",
        "Magazine Outdoor",
        "Magazine Parent",
        "Magazine People",
        "Magazine Photo",
        "Magazine PME",
        "Magazine Poker",
        "Magazine Politique",
        "Magazine Psychologie",
        "Magazine Religion et Spiritualité",
        "Magazine Satirique",
        "Magazine Santé",
        "Magazine Sciences et Techniques",
        "Magazine Sciences Occultes et Paranormal",
        "Magazine Séniors",
        "Magazine Sport",
        "Magazine Sport Mécanique",
        "Magazine Stars Célébrités",
        "Magazine Tatouage",
        "Magazine Théatre",
        "Magazine Télévision",
        "Magazine Tiercé - PMU - Turf",
        "Magazine Vins et Spiritueux",
        "Magazine Voyage",
        "Presse Professionnelle Agricole",
        "Presse Professionnelle Agroalimentaire",
        "Presse Professionnelle Assurance",
        "Magazines Arts de la Rue, Fête et Cirque",
        "Presse Professionnelle Automobile",
        "Presse Professionnelle Autres Service",
        "Presse Professionnelle Batiment",
        "Presse Professionnelle Bijoux - Magazine Bijoux",
        "Presse Professionnelle Biotech",
        "Presse Professionnelle Bois - Papier - Carton",
        "Presse Professionnelle Boulangerie",
        "Presse Professionnelle Chimie - Energie",
        "Presse Professionnelle Coiffure - Beauté",
        "Presse Professionnelle Communication",
        "Presse Professionnelle Cosmétique",
        "Presse Professionnelle Dentistes",
        "Presse Professionnelle Distribution",
        "Presse Professionnelle Finance",
        "Presse Professionnelle Environnement",
        "Magazines Fonction Publique",
        "Magazines Géopolitique - Défense",
        "Presse Professionnelle Industrie - Technique",
        "Presse Professionnelle Horticulture et Paysage",
        "Presse Professionnelle Informatique et Réseaux",
        "Presse Professionnelle Jeux et Jouets",
        "Presse Juridique - Magazine Juridique",
        "Presse Professionnelle Marketing",
        "Presse Professionnelle Métallurgie",
        "Presse Professionnelle Mode",
        "Presse Professionnelle Musique - Spectacle",
        "Presse Professionnelle Pêche",
        "Presse Professionnelle Petite Enfance",
        "Presse Professionnelle Photo",
        "Presse Professionnelle Restauration - Hotellerie",
        "Presse Professionnelle Ressources Humaines",
        "Presse Professionnelle Santé",
        "Presse Professionnelle Service Public",
        "Presse Professionnelle Sport",
        "Presse Professionnelle Textile",
        "Presse Professionnelle Tourisme",
        "Presse Professionnelle Transport",
        "Presse Professionnelle Urbanisme",
        "Presse Professionnelle Vin et Boissons",
        "Magazines Webdesign - Multimedia"
    );

    protected $formats =
        [
            [
                "support_id" => 2,
                "name" => "Pleine page intérieure"
            ],
            [
                "support_id" => 2,
                "name" => "2eme de couverture"
            ],
            [
                "support_id" => 2,
                "name" => "3eme de couverture"
            ],
            [
                "support_id" => 2,
                "name" => "4eme de couverture"
            ],
            [
                "support_id" => 2,
                "name" => "Format spécial"
            ],
            [
                "support_id" => 1,
                "name" => "4X3"
            ],
            [
                "support_id" => 1,
                "name" => "Mobilier Urbain"
            ],
            [
                "support_id" => 1,
                "name" => "Grand format"
            ],
            [
                "support_id" => 1,
                "name" => "Affichage Digital/Numérique"
            ],
            [
                "support_id" => 1,
                "name" => "Format divers"
            ],
        ];

    protected $ad_placement_types = array("hybrid", "booking", "auction");

    protected $users = array(
        [
            'first_name' => 'Killian',
            'family_name' => 'Blais',
            'email' => 'killian.blais@escaledigitale.com',
            'password' => 'u9bLWXEyI4br',
        ],
        [
            'first_name' => 'Hélène',
            'family_name' => 'Gloux',
            'email' => 'helene.gloux@escaledigitale.com',
            'password' => 'uNnxyW7CkFu7',
        ],
        [
            'first_name' => 'Julian',
            'family_name' => 'Didier',
            'email' => 'julian.didier@escaledigitale.com',
            'password' => '(BkLxQA28usM',
        ],
        [
            'first_name' => 'Mathieu',
            'family_name' => 'Le Gac',
            'email' => 'mathieu.legac@escaledigitale.com',
            'password' => '5cuyljFqYPp3',
        ],
        [
            'first_name' => 'David',
            'family_name' => 'Dokhan',
            'email' => 'david.dokhan@mediaresa.fr',
            'password' => 'FRpDWx3xQoTI',
        ],
        [
            'first_name' => 'Maxime',
            'family_name' => 'Pauvert',
            'email' => 'maxime.pauvert@escaledigitale.com',
            'password' => 'azertyuiop',
        ],
        [
            'first_name' => 'Bérénice',
            'family_name' => 'Kesteloot',
            'email' => 'berenice.kesteloot@escaledigitale.com',
            'password' => 'azerty',
        ],
        [
            'first_name' => 'Anita',
            'family_name' => 'Goulay',
            'email' => 'anita.goulay@escaledigitale.com',
            'password' => 'azerty',
        ]
    );

    protected $technical_supports = array(
        [
            "name" => "Forfait découverte",
            "description" => "Tarif comprenant : Création au format du support sélectionné, uniquement sur la base des templates proposés et d'un brief renseigné. Recherche iconographique (achat en supplément). Remise des livrables par email. 3 allers retours de corrections. Temps d'exécution : 6h",
            "price" => 599.00
        ],
        [
            "name" => "Forfait basique",
            "description" => "Tarif comprenant : Remise au format du support sélectionné, uniquement sur la base de fichiers PDF. 1 modification. 2 allers retours de corrections. Temps d'exécution : 4h",
            "price" => 499.00
        ],
        [
            "name" => "Forfait Expert",
            "description" => "Tarif comprenant : Remise au format du support sélectionné, uniquement sur la base de fichiers natifs (In design, Quark Xpress, Adobe Photoshop/Illustrator) et des polices de caractères fournies. 2 modifications maximum. 2 allers retours de corrections. Temps d'exécution : 2h",
            "price" => 299.00
        ]
    );

    protected $templates = array(
        [
            "name" => "PLEINE PAGE",
            "description" => "Photo(s) pleine page\nTitre, peu de texte\nMentions légales, baseline, logo",
            "cover" =>"formats-A4-1.png"
        ],
        [
            "name" => "APPEL À L’ACTION",
            "description" => "Grand(s) photo(s) ou fond couleur\nText de l’appel\nMentions légales, baseline, logo\nBon a renvoyer",
            "cover" =>"formats-A4-2.png"
        ],
        [
            "name" => "BUSINESS",
            "description" => "Grand(s) photo(s) en haut\nTitre(s)\nTexte redactionnel\nMentions légales, baseline, logo",
            "cover" =>"formats-A4-3.png"
        ],
        [
            "name" => "REDACTIONNEL",
            "description" => "Logo\nPetit(s) photo(s) \nTitre(s), chapeau\nTexte redactionnel\nMentions légales, baseline, logo, picto",
            "cover" =>"formats-A4-4.png"
        ],
        [
            "name" => "50/50",
            "description" => "Titre(s), chapeau\nGrands(s) photo(s) hauteur\nTitre(s), chapeau\nLogo\nTexte redactionnel ou offre\nPictos or graphiques\nMentions légales, baseline",
            "cover" =>"formats-A4-5.png"
        ],
        [
            "name" => "REDACTIONNEL COURT",
            "description" => "Logo\nFond couleur(s) ou photo(s)\nTitre(s), chapeau\nTexte redactionnel court\nMentions légales, baseline, logo",
            "cover" =>"formats-A4-6.png"
        ],
        [
            "name" => "CLASSIQUE",
            "description" => "Logo\nTitre(s)\nGrand(s) photo(s) en largeur\nTexte redactionnel\nMentions légales, baseline, picto(s)",
            "cover" =>"formats-A4-7.png"
        ],
        [
            "name" => "MULTI-IMAGE",
            "description" => "Titre(s)\nMultiple(s) image(s) avec description\nTexte redactionnel\nMentions légales, baseline, picto(s), logo",
            "cover" =>"formats-A4-8.png"
        ],
        [
            "name" => "PROMO",
            "description" => "Titre(s)\nMultiple(s) image(s) avec description\nPictos\nTexte redactionnel\nMentions légales, baseline, logo",
            "cover" =>"formats-A4-9.png"
        ],
        [
            "name" => "PLEINE LARGEUR",
            "description" => "Photo(s) pleine page\nTitre, peu de texte\nMentions légales, baseline, logo",
            "cover" =>"format-A4-italienne-1.png"
        ],
        [
            "name" => "ITALIENNE",
            "description" => "Titre(s)\nGrand(s) photo(s) panoramique\nTexte redactionnel\nMentions légales, baseline, logo , picto(s)",
            "cover" =>"format-A4-italienne-2.png"
        ],
        [
            "name" => "PAYSAGE",
            "description" => "Logo\nTitre(s)\nGrand(s) photo(s) en largeur\nTexte redactionnel\nMentions légales, baseline, picto(s)",
            "cover" =>"format-A4-italienne-3.png"
        ],
    );

    public function __construct()
    {
        $this->faker = Faker::create();
    }

    protected function address_empty_or_not()
    {
        return array("", $this->faker->address);
    }
}
