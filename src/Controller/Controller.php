<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller
{

    public function index(Request $request): Response {

        $name = $request->get('name');
        if (empty($name)) {
            $name = "world";
        }
        return new Response('Hello ' . $name .' !');
    }

}