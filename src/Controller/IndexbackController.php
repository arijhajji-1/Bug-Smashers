<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexbackController extends AbstractController
{
    /**
     * @Route("/indexback", name="indexback")
     */
    public function index(): Response
    {
        return $this->render('indexback/index.html.twig', [
            'controller_name' => 'IndexbackController',
        ]);
    }

    /**
     * @Route("/indexback_reclamation", name="reclamation")
     */
    public function reclamation(): Response
    {
        return $this->render('indexback/listeReclamation.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
