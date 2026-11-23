<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $slugify = new Slugify();

        // gestion d'un admin 
        $admin = new User();
        $admin->setFirstName("Admin")
            ->setLastName("Admin")
            ->setEmail("admin@myepse.be")
            ->setPassword($this->passwordHasher->hashPassword($admin,"password"))
            ->setIntroduction($faker->sentence())
            ->setDescription("<p>".join("</p><p>",$faker->paragraphs(3))."</p>")
            ->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);


        // gestion des users
        $users = []; // initialisation d'un tableau pour récup des user pour les Ad
        $genres = ['male','femelle'];

        for($u = 1; $u <= 10; $u++)
        {
            $user = new User();
            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1,99).'.jpg';
            $picture .= ($genre =='male' ? 'men/' : 'women/').$pictureId;

            $hash = $this->passwordHasher->hashPassword($user,'password');

            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName())
                ->setEmail($faker->email())
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>'.join('</p><p>',$faker->paragraphs(3)).'</p>')
                ->setPassword($hash)
                ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user; // ajouter l'utilisateur au tableau pour les annonces
        }

        for($i = 1; $i <= 30; $i++)
        {
            $ad = new Ad();
            $title = $faker->sentence();
            //$slug = $slugify->slugify($title);
            $coverImage = 'https://picsum.photos/id/'.$i.'/1000/350';
            $introduction = $faker->paragraph(2);
            $content = '<p>'.join('</p><p>',$faker->paragraphs(5)).'</p>';

            // liaison avec User
            $user = $users[rand(0, count($users)-1)];

            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(rand(40,200))
                ->setRooms(rand(1,5))
                ->setAuthor($user);

            $manager->persist($ad);

             // Gestion de l'image de l'annonce
            for($j = 1; $j <= rand(2,5); $j++)
            {
                $image = new Image();
                $image->setUrl('https://picsum.photos/id/'.$j.'/900')
                    ->setCaption($faker->sentence())
                    ->setAd($ad);
                $manager->persist($image);
            }

        }

        $manager->flush();
    }
}