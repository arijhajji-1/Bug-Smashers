<?php

namespace App\Controller;
use App\Entity\AvisReparation;
use App\Form\AvisReparationType;
use App\Entity\Reparation;
use App\Repository\AvisReparationRepository;
use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisReparationController extends AbstractController
{
    /**
     * @Route("/avis", name="avis_reparation")
     */
    public function index(): Response
    {
        return $this->render('reparation/avis.html.twig', [
            'controller_name' => 'AvisReparationController',
        ]);
    }
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @route("/avis/{id}" , name="avis_reparation")
     */
    function addAvis(Request $request,Reparation $id){
        $AvisReparation=new AvisReparation() ;
        $AvisReparation->setIdrep($id);
        $AvisReparation->setNom($this->getUser()->getFirstName());
        $AvisReparation->setIduser($this->getUser()->getId());

        $AvisReparation->setEmail($this->getUser()->getEmail());
        $form=$this->createForm(AvisReparationType::class,$AvisReparation);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();

            $em->persist($AvisReparation);
            $em->flush();
            return $this->redirectToRoute("reparation");


        }

        return $this->render('reparation/avis.html.twig',['form'=>$form->createView(),'AvisReparation'=>$AvisReparation]);


    }

}
