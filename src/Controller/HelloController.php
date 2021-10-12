<?php
namespace App\Controller;

use App\Taxes\Calculator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{
    protected $logger;
    protected $calculator;

    public function __construct(LoggerInterface $logger, Calculator $calculator)
    {
        $this->logger = $logger;
        $this->calculator = $calculator;
        
    }

    /**
     * @Route("/hello/{name<[^0-9]+>?World}", name="hello", methods={"GET", "POST"})
     */
    // [^0-9]+ nom numérique  [a-zA-Z] seulement les lettres


    public function hello($name, Calculator $calculator, LoggerInterface $logger)
    {

        $this->logger->info("Mon message de log");
        $tva = $this->calculator->calcul(100);
        dump($tva);
        return new Response("hello $name ");
    }
}
