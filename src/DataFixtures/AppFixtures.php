<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        $dataCategories = [];
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setName($faker->unique()->word());
            $manager->persist($category);
            $dataCategories[] = $category;
        }

        for ($i = 0; $i < 20; $i++) {
            $article = new Article();
            $article
                ->setTitle($faker->realText(30))
                ->setDescription($faker->sentence(150))
                ->setCategory($faker->randomElement($dataCategories));
            
            $manager->persist($article);
        }

        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $password = $this->hasher->hashPassword($admin, 'password');
        $admin->setPassword($password);
        $manager->persist($admin);

        for ($i = 1; $i < 6; $i++) {
            $user = new User();
            $user->setEmail('user'.$i.'@user.com');
            $user->setRoles(['ROLE_USER']);
            $password = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
