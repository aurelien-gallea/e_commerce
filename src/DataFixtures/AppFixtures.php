<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Produit;
use App\Entity\User;
use App\Entity\Vendeur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;
    public function __construct(private UserPasswordHasherInterface $passwordHasher ) {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
       

        $client = new Client();
        $client->setEmail("client@client.fr")
        ->setPassword($this->passwordHasher->hashPassword($client, "client"));

        $vendeur = new Vendeur();
        $vendeur->setEmail("vendeur@vendeur.fr")
        ->setPassword($this->passwordHasher->hashPassword($vendeur, "vendeur"));



        for ($i=1; $i < 11; $i++) { 
            //produits
            $produit = new Produit();
            $produit->setPrixUnite($this->faker->randomFloat(2, 0, 80));
            $produit->setQteStock($this->faker->numberBetween(1, 499));
            $produit->setDesignation($this->faker->sentence());
            $manager->persist($produit);
        }

      
        $manager->persist($client);
        $manager->persist($vendeur);
        $manager->flush();
    }
}
