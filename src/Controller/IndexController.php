<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
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
    public function produit(SessionInterface $session,ProduitRepository $produitRepository)
    {

        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll()
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
