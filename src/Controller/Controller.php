<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller
{
    /**
     * @Route("/", name="home")
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response {

        $name = $request->get('name');
        if (empty($name)) {
            $name = "world";
        }
        return new Response('Hello ' . $name .' !');
    }

}