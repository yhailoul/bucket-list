<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Wish;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher){

    }
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

        $users=[];
        for($i = 0; $i < 50; $i++) {
            $user = new User();
            $user
                ->setRoles(['ROLE_USER'])
                ->setEmail($faker->email())
                ->setPseudo($faker->userName())
                ->setPassword(
                    $this->userPasswordHasher->hashPassword($user, '123456'));
            $manager->persist($category);
            $users[] = $user;
        }

        $manager->flush();

        for ($i = 0; $i < 50; $i++) {
            $wish = new Wish();
            $wish
                ->setTitle($faker->sentence(6,[true]))
                ->setDateCreated($faker->dateTimeBetween('-5 years','now'))
                ->setDateUpdated($faker->dateTimeBetween('-5 years','now'))
                ->setUser($faker->randomElement($users))
                ->setDescription($faker->text(255))
                ->setIsPublished(true)
                ->setCategory($faker->randomElement($categories));

            $manager->persist($wish);
        }

        $manager->flush();
    }
}
