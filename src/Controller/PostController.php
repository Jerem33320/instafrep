<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     */
    public function index()
    {
        // On récupère tous les Posts publics
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy([
           'public' => true,
        ]);

        // On envoie les posts dans la vue
        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }
}
