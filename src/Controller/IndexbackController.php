<?php

namespace App\Controller;
use App\Entity\Montage;
use App\Entity\Reparation;
use App\Form\EtatType;
use App\Repository\MontageRepository;
use App\Repository\ReparationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @route("/montage1",name="montage1")
     */
    public function AfficheP(){
        $repo=$this->getDoctrine()->getRepository(Montage::class) ;
        $montage=$repo->findAll();
        return $this->render('indexback/montage.html.twig',['montage'=>$montage]);

    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @route("/reparation1",name="reparation1")
     */
    public function AfficheR(){
        $repo=$this->getDoctrine()->getRepository(Reparation::class) ;
        $reparation=$repo->findAll();
        return $this->render('indexback/reparation.html.twig',['reparation'=>$reparation]);

    }
    /**
     * @Route ("/updateback/{id}", name="updateback")
     */
    public function UpdateReparation(ReparationRepository $repo,$id,Request $request)
    {
        $reparation=$repo->find($id);
        $form=$this->createForm(EtatType::class,$reparation);
        $form->add('update',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('reparation1');
        }
        return $this->render('indexback/updaterep.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}
