<?php


namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class Controller extends AbstractController
{
    /**
     * @Route("/", name="home")
     *
     * @param Request $request
     * @return Response
     */
    public function index(AuthenticationUtils $authenticationUtils, Request $request): Response {

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

        $totalPages = ceil($totalPosts / 5);

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // On récupère les Posts publics correspondants à la page demandée
        $posts = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->findHomepage($start);


        // On envoie les posts dans la vue
        if ($request->isXmlHttpRequest()) {

//            sleep(rand(1, 2));
            return $this->render('post/list.html.twig', [
                'posts' => $posts
            ]);
        }

        return $this->render('homepage.html.twig', [
            'posts' => $posts,
            'last_username' => $lastUsername,
            'error' => $error,
            'pagination' => [
                'current' => $page,
                'max' => $totalPages,
            ]
        ]);
    }


    /**
     * @Route("/rand", name="random_number")
     *
     * @param Request $request
     * @return Response
     */
    public function randomPage(Request $request) {

        $rand = rand(0, 100);

        // Si c'est une requête AJAX,
        // on répond uniquement le nombre
        $isAjax = $request->isXmlHttpRequest();
        if ($isAjax === true) {
            sleep(rand(1, 3));

            return new Response($rand);
        }

        // Si c'est une requête "normale", on rend une page HTML
        return $this->render('random.html.twig', [
            'rand' => $rand
        ]);
    }

}