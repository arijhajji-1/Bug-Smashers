<?php

namespace App\Controller;

use App\Entity\LigneCommande;
use App\Form\LigneCommande1Type;
use App\Repository\LigneCommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ligne/commande")
 */
class LigneCommandeController extends AbstractController
{
    /**
     * @Route("/", name="ligne_commande_index", methods={"GET"})
     */
    public function index(LigneCommandeRepository $ligneCommandeRepository): Response
    {
        return $this->render('ligne_commande/index.html.twig', [
            'ligne_commandes' => $ligneCommandeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ligne_commande_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ligneCommande = new LigneCommande();
        $form = $this->createForm(LigneCommande1Type::class, $ligneCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ligneCommande);
            $entityManager->flush();

            return $this->redirectToRoute('ligne_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ligne_commande/new.html.twig', [
            'ligne_commande' => $ligneCommande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_commande_show", methods={"GET"})
     */
    public function show(LigneCommande $ligneCommande): Response
    {
        return $this->render('ligne_commande/show.html.twig', [
            'ligne_commande' => $ligneCommande,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ligne_commande_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, LigneCommande $ligneCommande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LigneCommande1Type::class, $ligneCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('ligne_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ligne_commande/edit.html.twig', [
            'ligne_commande' => $ligneCommande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_commande_delete", methods={"POST"})
     */
    public function delete(Request $request, LigneCommande $ligneCommande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ligneCommande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ligneCommande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ligne_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}
