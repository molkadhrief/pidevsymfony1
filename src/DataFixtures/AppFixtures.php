<?php

namespace App\DataFixtures;
use App\Entity\Logement;
use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Création de quelques catégories
        $types = ['Appartement', 'Maison', 'Studio', 'Chambre', 'Autre']; // Ajoutez d'autres types si nécessaire

        foreach ($types as $type) {
            $categorie = new Categorie();
            $categorie->setType($type);
            $manager->persist($categorie);
        }

        // Création de quelques logements avec des catégories associées
        for ($i = 0; $i < 10; $i++) {
            $logement = new Logement();
            $logement->setNom($faker->words(2, true));
            $logement->setDescription($faker->paragraph);
            $logement->setPlace($faker->numberBetween(1, 10));
            $logement->setPrix($faker->randomFloat(2, 50, 500));

            // Sélectionnez une catégorie aléatoire parmi celles créées précédemment
            $categorie = $manager->getRepository(Categorie::class)->findOneBy([]); // Sélectionne une catégorie existante
            $logement->setCategorie($categorie);

            $manager->persist($logement);
        }

        $manager->flush();
    }
}
