<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class Controller
{

    public function index() {

        return new Response('Hello world');
    }

}