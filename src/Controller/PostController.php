<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/posts", name="posts_list")
     */
    public function index(Request $request)
    {

        // Gestion de la pagination
        $page = (int) $request->query->get('p');

        if (!isset($page)) {
            $page = 1;
        }

        $page = max(1, $page);
        $start = ($page - 1) * 5;
        $totalPosts = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->countForHomepage();

        $max =ceil($totalPosts / 5);

        // On récupère tous les Posts publics
        $posts = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->findHomepage($start);

        $form = $this->createForm(PostType::class);

        // On envoie les posts dans la vue
        return $this->render('post/index.html.twig', [
            // variable twig => variable PHP
            'posts' => $posts,
            'post_form' => $form->createView(),
            'pagination' => [
                'current' => $page,
                'max' => $max, // fake limit for now
            ]
        ]);
    }

    /**
     * @Route("/posts/{id}", name="post_single", requirements={"id"="[0-9]+"})
     */
    public function single($id) {

        // On va chercher en BDD le post qui correspond à l'ID
        $post = $this->findOr404($id);

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
                /** @var Post $post */
                $post = $form->getData();
                $post->setAuthor($this->getUser());

                // gestion du fichier
                /** @var UploadedFile $file */
                $file = $post->getAttachment();
                if (!empty($file)) {
                    // renomme le fichier
                    $basename = 'post-attach-' . md5(uniqid());
                    $ext = $file->guessExtension();
                    $filename = $basename . '.' . $ext;

                    $file->move($this->getParameter('user_upload_folder'), $filename);

                    // on force attachment en string pour l'envoi en BDD
                    $post->setAttachment($filename);
                }

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
        /** @var Post $post */
        $post = $this->findOr404($id);

        $fileName = $post->getAttachment();
        $oldFile = null;
        if (!empty($fileName)) {
            $oldFile = new File($this->getParameter('user_upload_folder') . '/' . $fileName);
            $post->setAttachment($oldFile);
        }

        $response = new Response();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ( $form->isSubmitted() ) {

            if ($form->isValid()) {

                $post = $form->getData();
                // gestion du fichier
                /** @var UploadedFile $file */
                $newFile = $post->getAttachment();
                if (!empty($newFile)) {
                    // renomme le fichier
                    $basename = 'post-attach-' . md5(uniqid());
                    $ext = $newFile->guessExtension();
                    $filename = $basename . '.' . $ext;

                    $newFile->move($this->getParameter('user_upload_folder'), $filename);

                    // on force attachment en string pour l'envoi en BDD
                    $post->setAttachment($filename);

                    // On supprime le vieux fichier !
                    if (!empty($oldFile)) {
                        $fileSystem = new Filesystem();
                        $fileSystem->remove($oldFile->getPathname());
                    }
                }



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

        $post = $this->findOr404($id);

        // on supprime
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($post);
        $manager->flush();

        // on redirige
        return $this->redirectToRoute('posts_list');
    }


    /**
     * @Route("posts/{id}/like", name="post_like")
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function like(Request $request, $id) {

        $post = $this->findOr404($id);

        $this->getUser()->like($post);

        $manager = $this->getDoctrine()->getManager();
        $manager->flush();

        if ($request->isXmlHttpRequest()) {
            /**
             * @see https://fr.wikipedia.org/wiki/Liste_des_codes_HTTP
             */
            return new Response('', 201);
        }

        // Naive redirect to the previous page
        $referer = $request->headers->get('referer');

        if (!$referer) {
            return $this->redirectToRoute('post_single', ['id' => $id]);
        }

        return $this->redirect($referer);

    }

    /**
     * @Route("posts/{id}/unlike", name="post_unlike")
     * @param $id
     */
    public function unlike(Request $request, $id) {

        $post = $this->findOr404($id);

        $this->getUser()->unlike($post);

        $manager = $this->getDoctrine()->getManager();
        $manager->flush();

        if ($request->isXmlHttpRequest()) {
            /**
             * @see https://fr.wikipedia.org/wiki/Liste_des_codes_HTTP
             */
            return new Response(null, 204);
        }
        // Naive redirect to the previous page
        $referer = $request->headers->get('referer');

        if (!$referer) {
            return $this->redirectToRoute('post_single', ['id' => $id]);
        }

        return $this->redirect($referer);
    }


    private function findOr404($id) {

        $post = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        if (empty($post)) {
            throw $this->createNotFoundException('Post introuvable');
        }

        return $post;
    }
}
