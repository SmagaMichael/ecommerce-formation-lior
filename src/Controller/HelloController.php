<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{

    /**
     * @Route("/hello/{name<[^0-9]+>?World}", name="hello", methods={"GET", "POST"})
     */
    // [^0-9]+ nom numérique 
    // [a-zA-Z] seulement les lettres


    public function hello($name)
    {
        return new Response("hello $name ");
    }
}
