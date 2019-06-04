<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{

    /**
     * @Route("/image", name="image")
     */
    public function index()
    {
        $imageSize = 200;

        // On crée les "ressources"
        $img = imagecreate($imageSize * 10, $imageSize);
        $leftPart = imagecreate($imageSize / 2, $imageSize);
        $rightPart = imagecreate($imageSize / 2, $imageSize);

        imagecolorallocate($leftPart, 255, 255, 255);

        $size = 20;

        $cols = $imageSize / 2 / $size; // 5 cols
        $rows = $imageSize / $size; // 10 rows

        $squareColor = imagecolorallocate($leftPart, rand(0, 255), 0, 0);

        // Generation du motif
        for ($i = 0; $i < $cols; $i++) {
            for ($j = 0; $j < $rows; $j++) {

                $leaveBlank = rand(1, 2) === 1;

                if ($leaveBlank) continue;

                $x = $i * $size;
                $y = $j * $size;

                imagefilledrectangle(
                    $leftPart,
                    $x, // 0
                    $y, // 0
                    $x + $size, // 20
                    $y + $size, // 20
                    $squareColor
                );

            }
        }

        // Copier l'autre partie et la retourner
        imagecopy($rightPart, $leftPart, 0, 0, 0, 0, $imageSize / 2, $imageSize);
        imageflip($rightPart, IMG_FLIP_HORIZONTAL);

        // Créer l'image finale
        imagecopy($img, $leftPart, $i * $imageSize, 0, 0, 0, $imageSize / 2, $imageSize);
        imagecopy($img, $rightPart, 100, 0, 0, 0, $imageSize / 2, $imageSize);


        // Créé un fichier PNG a partir de la ressource (toujours dans la mémoire !)
        ob_start(); // démarrage de la zone "tampon"
        imagepng($img, null,  9); // on écrit dans le tampon
        $content = ob_get_contents(); // on récupère le contenu du tampon
        ob_get_clean(); // on nettoie le tampon

        // Creation de la réponse HTTP
        $response = new Response($content);
        $response->headers->set('Content-Type', 'image/png');

        return $response;
    }
}
