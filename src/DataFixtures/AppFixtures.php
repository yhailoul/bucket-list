<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        $categoryNames = [
            'Travel & Adventure',
            'Sport',
            'Entertainment',
            'Human Relations',
            'Others'
        ];

        $categories = []; // Pour stocker les objets Category créés

        foreach ($categoryNames as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $categories[] = $category;
        }

        $manager->flush();

        for ($i = 0; $i < 50; $i++) {
            $wish = new Wish();
            $wish
                ->setTitle($faker->sentence(6,[true]))
                ->setDateCreated($faker->dateTimeBetween('-5 years','now'))
                ->setDateUpdated($faker->dateTimeBetween('-5 years','now'))
                ->setAuthor($faker->userName())
                ->setDescription($faker->text(255))
                ->setIsPublished(true)
                ->setCategory($faker->randomElement($categories));

            $manager->persist($wish);
        }

        $manager->flush();
    }
}
