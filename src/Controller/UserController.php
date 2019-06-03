<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users/{id<\d+>}", name="user_profile")
     */
    public function profile($id)
    {
        if(intval($id) === $this->getUser()->getId()) {
            return $this->redirectToRoute('current_user_profile');
        }

        $user = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if (empty($user)) {
            throw $this->createNotFoundException('User #' . $id . " not found");
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }


    /**
     * @Route("/users/me", name="current_user_profile")
     */
    public function me(Request $req) {

        $user = $this->getUser();

        $form = $this->createForm(UserProfileType::class, $user);

        $form->handleRequest($req);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
            }
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);


    }


}
