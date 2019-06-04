<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

        $manager = $this->getDoctrine()->getManager();

        /** @var $user User */
        $user = $this->getUser();
        $oldAvatar = $user->getAvatar();
        if ($oldAvatar) {
            $user->setAvatar(
                new File($this->getParameter('user_upload_folder').'/'.$oldAvatar)
            );
        }

        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {

                // Traitement du fichier
                /* @var UploadedFile $file  */
                $file = $user->getAvatar();
                if ($file) {
                    // On renomme le fichier
                    $ext = $file->guessExtension();
                    $basename = 'profile-picture-' . $user->getId();
                    $filename = $basename . '.' . $ext;

                    // TODO: remove existing file if any

                    // Puis on l'enregistre dans le dosser public/uploads
                    $file->move($this->getParameter('user_upload_folder'), $filename);
                    $user->setAvatar($filename);
                }

                try {
                    // Enregistrement en BDD
                    $manager->persist($user);
                    $manager->flush();

                    $this->redirectToRoute('current_user_profile');
                } catch (\Exception $e) {
                    // TODO: remove uploaded file, if database throws any error
                }
            }
        }


        // @HACK: reset the avatar to string so that it can be serialized
        $formView = $form->createView();
        $user->setAvatar($oldAvatar);

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'form' => $formView,
        ]);


    }


}
