<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Repository\AvisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    /**
     * @Route("/avis", name="avis")
     */
    public function index(): Response
    {
        return $this->render('avis/index.html.twig', [
            'controller_name' => 'AvisController',
        ]);
    }
    /**
     * @Route("/produit/affback/avis", name="avis_back")
     */
    public function backAvis(AvisRepository $avisRepository): Response
    {
        return $this->render('produit/avisback.html.twig', [
            'Avis' => $avisRepository->findAll(),
        ]);
    }
    /**
     * @Route("/supprimer_avis/{id}", name="supprimer_avis")
     */
    public function deleteProductA(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $avis = $entityManager->getRepository(Avis::class)->find($id);
        $entityManager->remove($avis);
        $entityManager->flush();

        return $this->redirectToRoute("avis_back");
    }
}
