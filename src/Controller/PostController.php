<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/posts", name="posts_list")
     */
    public function index()
    {
        // On récupère tous les Posts publics
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy([
           'public' => true,
        ]);

        // On envoie les posts dans la vue
        return $this->render('post/index.html.twig', [
            // variable twig => variable PHP
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/posts/{id}", name="post_single", requirements={"id"="[0-9]+"})
     */
    public function single($id) {

        // On va chercher en BDD le post qui correspond à l'ID
        $post = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        // Si le post n'est pas trouvé, on doit gérer cette erreur
        if (empty($post)) {
            // rediriger sur une autre page
            // return $this->redirectToRoute('posts_list');
            // ou lancer une erreur 404
            throw $this->createNotFoundException('Post introuvable');
        }

        // On passe le post trouvé à la vue
        return $this->render('post/single.html.twig', [
            'post' => $post
        ]);
    }


    /**
     * @Route("posts/new", name="post_create")
     */
    public function create() {

        $form = $this->createForm(PostType::class);

        return $this->render('post/create.html.twig', [
            'post_form' => $form->createView()
        ]);
    }


}
