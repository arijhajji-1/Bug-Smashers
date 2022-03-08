<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Livraison;
use App\Form\Livraison1Type;
use App\Repository\LivraisonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;

/**
 * @Route("/livraison")
 */
class LivraisonController extends AbstractController
{
    /**
     * @Route("/list/livraison", name="livraison_index", methods={"GET"})
     */
    public function index(LivraisonRepository $livraisonRepository): Response
    {
        $livraison = $this->getDoctrine()->getRepository(Livraison::class)->findAll();
        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraison
        ]);
       /* return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisonRepository->findAll(),
        ]);*/
    }

    /**
     * @Route("/add/livraison", name="livraison_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager , FlashyNotifier $flashy): Response
    {
        $livraison = new Livraison();
        $livraison->setDate(new \Datetime());

       // $commande = new Commande();
        $commande = $this->getDoctrine()->getRepository(Commande::class)->findOneByid(12);

        $livraison->setCommande($commande);
        $form = $this->createForm(Livraison1Type::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livraison);
            $entityManager->flush();
            $this->addFlash('success', 'livraison enregistrée!');
            return $this->redirectToRoute('livraison_index', [], Response::HTTP_SEE_OTHER);
            //$flashy->success('livraison enregistrée!', 'http://your-awesome-link.com');
        }

        return $this->render('livraison/new.html.twig', [
            'livraison' => $livraison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/list/{id}", name="livraison_show", methods={"GET"})
     */
    public function show(Livraison $livraison): Response
    {
        return $this->render('livraison/show.html.twig', [
            'livraison' => $livraison,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="livraison_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Livraison $livraison, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(Livraison1Type::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'livraison modifiée !');
            return $this->redirectToRoute('livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livraison/edit.html.twig', [
            'livraison' => $livraison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="livraison_delete", methods={"POST"})
     */
    public function delete(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livraison->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livraison);
            $entityManager->flush();
            $this->addFlash('success', 'livraison supprimée !');
        }

        return $this->redirectToRoute('livraison_index', [], Response::HTTP_SEE_OTHER);
    }
}
