<?php

namespace App\Controller;
use App\Data\SearchDataReparation;
use App\Entity\AvisReparation;
use App\Entity\Montage;
use App\Entity\User;

use App\Entity\Reparation;
use App\Form\EtatType;
use App\Form\SearchFormReparation;
use App\Message\GenerateReport;
use App\Repository\AvisReparationRepository;
use App\Repository\MontageRepository;
use App\Repository\ReparationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     *
     * @Route("/reparation1", name="reparation1")
     */
    public function backreparation(ReparationRepository $reparationRepository, Request $request): Response
    {
        $data = new SearchDataReparation();
        $form = $this->createForm(SearchFormReparation::class,$data, [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        $reparation=$reparationRepository->findSearch($data);
        return $this->render('indexback/reparation.html.twig', [
            'reparation' => $reparation,
            'form' => $form->createView(),
        ]);
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
            $this->dispatchMessage(new GenerateReport($reparation->getEmail(), $reparation->getTelephone()));

            return $this->redirectToRoute('reparation1');
        }
        return $this->render('indexback/updaterep.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/afficheavis", name="AvisReparation_show")
     */
    public function show(AvisReparationRepository $repo)
    {
        $repo=$this->getDoctrine()->getRepository(AvisReparation::class);
        $AvisReparation=$repo->findAll();
        return $this->render('indexback/afficheAvis.html.twig',[
            'AvisReparation'=>$AvisReparation
        ]);
    }
    /**
     * @Route("/inv", name="reparer")
     */
    public function reparationAction(ReparationRepository $reparationRepository,Request $request)
    {
        $data = new SearchDataReparation();
        $form = $this->createForm(SearchFormReparation::class, $data, [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        if ($request->isXmlHttpRequest()) {
            $reparation = $reparationRepository->findSearch($data);
            foreach ($reparation as $item) {
                $arrayCollection[] = array(
                    'id' => $item->getId(),
                    'type' => $item->getType(),
                    'description' => $item->getDescription(),
                    'category' => $item->getCategory(),
                    'Reserver' => $item->getReserver(),
                    'email' => $item->getEmail(),
                    'etat' => $item->getEtat(),
                );
            }
            return new JsonResponse($arrayCollection);
        }
    }
}
