<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AgeController extends AbstractController
{


    public function guessYear($age): ?int {
        return is_numeric($age) ? date('Y') - $age : null;
    }

    /**
     * Guess the user year of birth from his age
     *
     * @Route("/year", name="age")
     */
    public function getBirthYear(Request $request)
    {
        $age = $request->get('age');

        return $this->render('age/index.html.twig', [
            'year' => $this->guessYear($age),
            'username' => 'pierre'
        ]);
    }
}
