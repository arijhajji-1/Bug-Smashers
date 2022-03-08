<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FactureController extends AbstractController
{
    /**
     * @Route("/facture", name="facture")
     */
    public function index(): Response
    {
        return $this->render('facture/index.html.twig', [
            'controller_name' => 'FactureController',
        ]);
    }
}
