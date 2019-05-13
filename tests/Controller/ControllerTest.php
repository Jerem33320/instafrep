<?php

namespace App\Tests\Controller;

use App\Controller\Controller;

use \PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ControllerTest extends TestCase
{

    public function testIndex_withoutGetParameter() {

        // On crée tout ce dont on a besoin pour faire notre test
        $controller = new Controller();
        $request = new Request();

        // On teste effectivement la méthode index
        $result = $controller->index($request);

        // On vérifie les résultats
        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals('Hello world !', $result->getContent());
    }

    public function testIndex_withInvalidGetParameter() {
        // On crée tout ce dont on a besoin pour faire notre test
        $controller = new Controller();
        $request = new Request();
        $request->query->set('name', '');

        // On teste effectivement la méthode index
        $result = $controller->index($request);

        // On vérifie les résultats
        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals('Hello world !', $result->getContent());

    }

    public function testIndex_withValidGetParameter() {
        // On crée tout ce dont on a besoin pour faire notre test
        $controller = new Controller();
        $request = new Request();
        $request->query->set('name', 'Pierre');

        // On teste effectivement la méthode index
        $result = $controller->index($request);

        // On vérifie les résultats
        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals('Hello Pierre !', $result->getContent());

    }

}
