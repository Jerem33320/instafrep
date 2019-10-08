<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

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

            $password = $this->encoder->encodePassword($user,'aze');
            $user->setPassword($password);

            $manager->persist($user);
            array_push($users, $user);

        }


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

            // Auteur du post
            $k = array_rand($users);
            $author = $users[$k];
            $post->setAuthor($author);


            // Creation des commentaires
            $nbComments = rand(3, 8);
            for ($j = 0; $j < $nbComments; $j++) {

                $comment = new Comment();
                $comment->setContent($faker->realText(280));

                $key = array_rand($users);
                $commentAuthor = $users[$key];
                $comment->setAuthor($commentAuthor);

                // Liaison du commentaire à son post
                $comment->setPost($post);
                // OU
                $post->addComment($comment);

                // Persister le commentaire !!
                // (inutile ici, car automatiquement configuré dans l'entité Post (cascade))
                $manager->persist($comment);
            }


            // Creations des "likes"
            $nbLikers = rand(0, count($users));
            for ($j = 0; $j < $nbLikers; $j++) {
                $u = $users[$j];
                $u->like($post);
            }


            $manager->persist($post);
        }




        $manager->flush();
    }
}
