<?php

namespace App\Controller;

use App\Utils\AvatarGenerator;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{

    /**
     * @Route("/image", name="image")
     */
    public function index(Request $req, AvatarGenerator $generator)
    {
        // Creation de la réponse HTTP
        $response = new Response($avatar);
        $response->headers->set('Content-Type', 'image/png');

        return $response;
    }
}
