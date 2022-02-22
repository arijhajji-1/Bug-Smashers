<?php

namespace App\Controller;

use App\Entity\Reparation;

use App\Form\ReparationType;
use App\Repository\ReparationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;

class ReparationController extends AbstractController
{
    /**
     * @Route("/Reparation", name="reparation")
     */
    public function index(): Response
    {
        return $this->render('reparation/index.html.twig', [
            'controller_name' => 'ReparationController',
        ]);
    }
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @route("/Reparation", name="reparation")
     */
    function add(Request $request){
        $reparation=new Reparation() ;
        $form=$this->createForm(ReparationType::class,$reparation);
        $form->add('Ajouter',SubmitType::class) ;
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $reparation->setEtat("En cours");

            $em->persist($reparation);
            $em->flush();
            return $this->redirectToRoute("reparation");


        }

        return $this->render('Reparation/index.html.twig',['form'=>$form->createView()]);


    }
    /**
     * @Route("/afficherep", name="afficherep")
     */
    public function AfficheReparation(ReparationRepository $repo)
    {
        $repo=$this->getDoctrine()->getRepository(Reparation::class);
        $reparation=$repo->findAll();
        return $this->render('Reparation/afficherep.html.twig',[
            'reparation'=>$reparation
        ]);
    }
    /**
     * @Route("/delete1/{id}", name="delete1")
     */
    public function DeleteReparation($id, ReparationRepository $repo)
    {
        $reparation=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($reparation);
        $em->flush();
        return $this->redirectToRoute('afficherep');
    }
    /**
     * @Route ("/update1/{id}", name="update1")
     */
    public function UpdateReparation(ReparationRepository $repo,$id,Request $request)
    {
        $reparation=$repo->find($id);
        $form=$this->createForm(ReparationType::class,$reparation);
        $form->add('update',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('afficherep');
        }
        return $this->render('Reparation/update1.html.twig',[
            'form'=>$form->createView()
        ]);
    }

}
