<?php

namespace App\Controller;


use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="produit_index")
     */
    public function index(ProduitRepository $produitRepository)
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll()
        ]);
    }
}
