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
    public function index(AuthenticationUtils $authenticationUtils): Response {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // On récupère tous les Posts publics
        $posts = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->findHomepage();

        // On envoie les posts dans la vue
        return $this->render('homepage.html.twig', [
            'posts' => $posts,
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

}