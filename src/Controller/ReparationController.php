<?php

namespace App\Controller;

use App\Entity\Reparation;

use App\Form\ReparationType;
use App\Message\GenerateReport;
use App\Repository\ReparationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use App\Entity\Category;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        $reparation->setEmail($this->getUser()->getEmail());
        $reparation->setTelephone($this->getUser()->getTelephone());
        $form=$this->createForm(ReparationType::class,$reparation);
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
    /**
     * @route("/affichemobilerep",name="AfficheMobilerep")
     */
    public function Affichemobilerep(NormalizerInterface $normalizer,ReparationRepository $repo): Response
    {
        $repo=$this->getDoctrine()->getRepository(Reparation::class) ;
        $reparation=$repo->findAll();
        $jsonContent=$normalizer->normalize($reparation,'json',['groups'=>'post:read']);


        return new Response(json_encode($jsonContent));

dump($jsonContent);
die;

    }

    /**
     * @Route("/addreparation", name="add_reparation")
     * @Method("GET")
     */

    public function ajouterreparationAction(Request $request)
    {
        $reparation = new Reparation();
        $Category = $request->query->get("Category");
        $Type = $request->query->get("Type");
        $Description = $request->query->get("Description");

        $Reserver =  new \DateTime('now');
        $Etat = $request->query->get("Etat");
        $em = $this->getDoctrine()->getManager();

        $reparation->setType($Type);
        $reparation->setCategory($Category);
        $reparation->setDescription($Description);
        $reparation->setReserver($Reserver);
        $reparation->setEtat("En cours");

        $em->persist($reparation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reparation);
        return new JsonResponse($formatted);

    }
    /**
     * @Route("/deletereparation", name="delete_reparation")
     */

    public function deletereparationAction(Request $request) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $reparation = $em->getRepository(reparation::class)->find($id);
        if($reparation!=null ) {
            $em->remove($reparation);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("reparation a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id reparation invalide.");


    }

    /******************Modifier reparation*****************************************/
    /**
     * @Route("/updatereparation", name="update_reparation")
     */
    public function modifierreparationAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $reparation = $this->getDoctrine()->getManager()
            ->getRepository(reparation::class)
            ->find($request->get("id"));
        $reparation->setType($request->get("Type"));
        $reparation->setCategory($request->get("Category"));
        $reparation->setDescription($request->get("Description"));
        $reparation->setReserver($request->get("Reserver"));


        $em->persist($reparation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reparation);
        return new JsonResponse("reparation a ete modifiee avec success.");

    }
    /**
     * @Route("/detailreparation", name="detail_reparation")
     * @Method("GET")
     */

    //Detail reparation
    public function detailreparationAction(Request $request)
    {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $reparation = $this->getDoctrine()->getManager()->getRepository(reparation::class)->find($id);
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getDescription();
        });
        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($reparation);
        return new JsonResponse($formatted);
    }

}
