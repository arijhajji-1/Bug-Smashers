<?php

namespace App\Controller;
use App\Entity\AvisReparation;
use App\Form\AvisReparationType;
use App\Entity\Reparation;
use App\Repository\AvisReparationRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Repository\ReparationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
    /**
     * @Route("/addavisreparation", name="add_avisreparation")
     * @Method("GET")
     */

    public function ajouterAvisreparationAction(Request $request)
    {
        $Avisreparation = new AvisReparation();


        $Description = $request->query->get("Description");
$id=$request->query->get("idreparation");
        $email = $request->query->get("Email");
        $nom = $request->query->get("nom");
        $iduser = $request->query->get("UserId");
        $em = $this->getDoctrine()->getManager();
        $id = $em->getRepository(Reparation::class)->find($id);
$Avisreparation->setIdrep($id);
        $Avisreparation->setDescription($Description);
        $Avisreparation->setIduser($iduser);
        $Avisreparation->setEmail($email);
        $Avisreparation->setNom($nom);
        $em->persist($Avisreparation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Avisreparation);
        return new JsonResponse($formatted);

    }
    /**
     * @route("/affichemobileavisrep",name="AfficheMobileavisrep")
     */
    public function Affichemobileavisrep(NormalizerInterface $normalizer,AvisReparationRepository $repo): Response
    {
        $repo=$this->getDoctrine()->getRepository(AvisReparation::class) ;
        $avisreparation=$repo->findAll();
        $jsonContent=$normalizer->normalize($avisreparation,'json',['groups'=>'post:read']);


        return new Response(json_encode($jsonContent));

        dump($jsonContent);
        die;

    }
    /**
     * @Route("/deleteAvisreparations", name="delete_Avisreparation")
     * @Method ("DELETE")
     */
    public function deleteAvisreparationA(Request $request,  EntityManagerInterface $em)
    {
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $Reparation  = $em->getRepository(Reparation::class)->find($id);
        if($Reparation != null)
        {
            $em->remove($Reparation);
            $em->flush();

            return new JsonResponse("AvisReparation Deleted ");
        }
        return new JsonResponse("not found");
    }
    /**
     * @Route("/displayEn", name="displayEn")
     * Method ({"GET","POST"})
     */
    public function alls(NormalizerInterface $normalizer, ReparationRepository $repoS): Response
    {

        $em=$this->getDoctrine()->getManager();
        $blogs=$em->getRepository(AvisReparation::class)->findAll();

        $datas=array();
        foreach($blogs as $key=>$blog) {
            $datas[$key]['id']=$blog->getId();
            $datas[$key]['Description']=$blog->getDescription();

            $datas[$key]['Idreparation']=$blog->getIdrep()->getId();
            $datas[$key]['Email']=$blog->getEmail();
            $datas[$key]['UserId']=$blog->getIduser();
            $datas[$key]['nom']=$blog->getNom();
        }
        return new JsonResponse($datas);

    }
}
