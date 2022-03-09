<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Reply;
use App\Form\ReplyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReplyController extends AbstractController
{
    /**
     * @Route("/reply{id}", name="reply")
     */
    public function index($id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $avis = $entityManager->getRepository(Avis::class)->find($id);
        $reply = new Reply();
        $reply->setAvis($avis);
        $form = $this->createForm(ReplyType::class, $reply);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reply->setEmail("houssem");
            $reply->setNom("test");
            $entityManager->persist($reply);
            $entityManager->flush();
            $this->addFlash('success','Reply ajouté avec succès');

            return $this->redirectToRoute('evenement_event', ['id'=>$avis->getEvenement()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reply/index.html.twig', [
            'form' => $form->createView(),
            'avis'=> $avis,
        ]);
    }
}
