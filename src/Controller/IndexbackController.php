<?php

namespace App\Controller;
use App\Entity\Montage;
use App\Entity\Reparation;
use App\Repository\MontageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
