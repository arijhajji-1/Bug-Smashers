<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
}
