<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/posts", name="posts_list")
     */
    public function index()
    {
        // On récupère tous les Posts
        $posts = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->findPostList();

        $form = $this->createForm(PostType::class);

        // On envoie les posts dans la vue
        return $this->render('post/index.html.twig', [
            // variable twig => variable PHP
            'posts' => $posts,
            'post_form' => $form->createView()
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

        // On crée un formulaire pour les commentaires
        $form = $this->createForm(CommentType::class);

        // On passe le post trouvé à la vue
        return $this->render('post/single.html.twig', [
            'post' => $post,
            'comment_form' => $form->createView()
        ]);
    }


    /**
     * @Route("posts/new", name="post_create")
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request) {

        $response = new Response();

        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);

        if ( $form->isSubmitted() ) {

            if ($form->isValid()) {
                // on crée un nouvelle instance de l'entité Post
                $post = $form->getData();
                $post->setAuthor($this->getUser());

                // on dit à Doctrine de "s'occuper" de ce Post
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($post);

                // finalement, on dit au manager d'envoyer le post en BDD
                $manager->flush();

                return $this->redirectToRoute('posts_list');

            } else {
                $response->setStatusCode(400);
            }

        }

        return $this->render('post/create.html.twig', [
            'post_form' => $form->createView()
        ], $response);
    }

    /**
     * @Route("posts/{id}/edit", name="post_edit")
     * @param Request $request
     * @param $id the post id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, $id) {
        // On va chercher en BDD le post qui correspond à l'ID
        $post = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        // Si le post n'est pas trouvé, on doit gérer cette erreur
        if (empty($post)) {
            throw $this->createNotFoundException('Post introuvable');
        }

        $response = new Response();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ( $form->isSubmitted() ) {

            if ($form->isValid()) {

                // on dit au manager d'envoyer le post en BDD
                $manager = $this->getDoctrine()->getManager();
                $manager->flush();

                return $this->redirectToRoute('posts_list');

            } else {
                $response->setStatusCode(400);
            }

        }

        return $this->render('post/edit.html.twig', [
            'post_form' => $form->createView(),
            'post' => $post
        ], $response);
    }


    /**
     * @Route("posts/{id}/remove", name="post_remove")
     */
    public function remove($id) {

        // On va chercher en BDD le post qui correspond à l'ID
        $post = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        // Si le post n'est pas trouvé, on doit gérer cette erreur
        if (empty($post)) {
            throw $this->createNotFoundException('Post introuvable');
        }

        // on supprime
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($post);
        $manager->flush();

        // on redirige
        return $this->redirectToRoute('posts_list');
    }

}
