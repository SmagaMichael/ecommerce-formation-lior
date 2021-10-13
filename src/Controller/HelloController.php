<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{


    /**
     * @Route("/hello/{name<[^0-9]+>?World}", name="hello", methods={"GET", "POST"})
     */
    // [^0-9]+ nom numérique  [a-zA-Z] seulement les lettres
    public function hello($name = "world")
    {
        return $this->render('hello.html.twig', [
            'prenom' => $name,
        ]);
    }


    /**
     * @Route("/exemple", name="exemple")
     */
    public function exemple()
    {
        return $this->render('exemple.html.twig', [
            'age' => 33,
        ]);
    }

}
