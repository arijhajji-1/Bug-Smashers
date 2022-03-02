<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    /**
     * @Route("/", name="index")
     */
    public function HomePage(): Response
    {
        return $this->render('index/homePage.html.twig');
    }
    /**
     * @Route("/about", name="about")
     */
    public function about(): Response
    {
        return $this->render('index/about.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    /**
     * @Route("/produit", name="produit")
     */
    public function produit(): Response
    {
        return $this->render('index/produit.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    /**
     * @Route("/forum", name="forum")
     */
    public function forum(): Response
    {
        return $this->render('index/forum.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('index/contact.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    /**
     * @Route("/inscri", name="inscri")
     */
    public function inscri(): Response
    {
        return $this->render('index/inscri.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    /**
     * @Route("/montage", name="montage")
     */
    public function montage(): Response
    {
        return $this->render('index/montage.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
