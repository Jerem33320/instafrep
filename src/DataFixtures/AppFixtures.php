<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');

        // Création des Users
        $users = [];
        $usernames = ['Sleepy', 'Doc', 'Dopey'];
        $avatars = [
            'https://www.listchallenges.com/f/items-dl/a2b55c01-befc-4286-a245-0b3a8c8a2098.jpg',
            'https://www.listchallenges.com/f/items-dl/30b9cc37-1a6a-456a-97bf-f0121f5a0c26.jpg',
            'https://www.listchallenges.com/f/items-dl/e0fff511-3b03-4bd6-b5af-8c1d9d3671bd.jpg'
        ];


        foreach ($usernames as $k => $name) {

            $user = new User();
            $user->setUsername($name);
            $user->setEmail(strtolower($name) . '@mail.org');
            $user->setBirth($faker->dateTimeThisCentury());
            $user->setAvatar($avatars[$k]);

            $manager->persist($user);
            array_push($users, $user);

        }

        $users = $manager->getRepository(User::class)->findAll();

        // Création des Posts
        for ($i = 0; $i < 30; $i++) {

            $content = $faker->realText(280);
            $isPublic = $faker->boolean(70);

            $date = new \DateTime();

            $post = new Post();
            $post->setContent($content);
            $post->setPublic($isPublic);
            $post->setCreatedAt($date);
            $post->setPublishedAt($date->add(new \DateInterval('P1D')));


            $k = array_rand($users);
            $author = $users[$k];
            $post->setAuthor($author);

            $manager->persist($post);
        }

        $manager->flush();
    }
}
