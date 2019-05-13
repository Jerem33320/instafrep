<?php

namespace App\Tests\Controller;

use App\Controller\AgeController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class AgeControllerTest extends TestCase
{

    public function testGuessYear_withInvalidParam() {

        $controller = new AgeController();
        $result = $controller->guessYear('');
        $this->assertEquals(null, $result);

        $result = $controller->guessYear('azeaze');
        $this->assertEquals(null, $result);

        $result = $controller->guessYear(true);
        $this->assertEquals(null, $result);
    }

    public function testGuessYear_withValidParam() {
        $controller = new AgeController();
        $result = $controller->guessYear('10');
        $this->assertEquals(2009, $result); // ne marchera plus en 2020 !!!!!
    }
}
