<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EtatType;
use App\Message\GenerateReport;
use App\Entity\ProduitAcheter;
use Doctrine\ORM\EntityManagerInterface;
use \Symfony\Bundle\MonologBundle\SwiftMailer;
use App\Entity\ProduitAcheterType;
use App\Form\MontageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Montage;
use App\Repository\MontageRepository;
use App\Repository\UserRepository;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints as Assert;


class MontageController extends AbstractController
{

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
     * @route("/montage", name="addmontage")
     */
    function add(Request $request,\Swift_Mailer $mailer){
        $montage=new Montage() ;
        $montage->setEmail($this->getUser()->getEmail());
        $montage->setIduser($this->getUser()->getId());

        $form=$this->createForm(MontageType::class,$montage);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($montage);
            $em->flush();

            $message = (new \Swift_Message( 'Demande Montage!'))
                ->setFrom('Reloua.tunisie@gmail.com')

                ->setTo($montage->getEmail())
                ->setBody(

                    $this->renderView(
                        'montage/email.html.twig', compact('montage')
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);
            $this->addFlash('success', 'Montage ajouté');
            return $this->redirectToRoute("addmontage");


        }

        return $this->render('montage/montage.html.twig',['form'=>$form->createView()]);


    }

    /******************Modifier reparation*****************************************/
    /**
     * @Route("/updatemontage", name="update_montage")
     * @Method("PUT")
     */
    public function modifiermontageAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $montage = $this->getDoctrine()->getManager()->getRepository(montage::class)->find($request->get("idmontage"));
        $montage->setProcesseur($request->get("Processeur"));
        $montage->setCarteGraphique($request->get("CarteGraphique"));
        $montage->setCarteMere($request->get("CarteMere"));
        $montage->setDisqueSysteme($request->get("DisqueSysteme"));
        $montage->setBoitier($request->get("Boitier"));
        $montage->setStockageSupp($request->get("StockageSupp"));


        $em->persist($montage);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($montage);
        return new JsonResponse("montage a ete modifiee avec success.");

    }
    /**
     * @Route("/affiche", name="affiche")
     */
    public function AfficheMontage(MontageRepository $repo)
    {
        $repo=$this->getDoctrine()->getRepository(Montage::class);
        $montage=$repo->findAll();
        return $this->render('montage/affiche.html.twig',[
            'montage'=>$montage
        ]);
    }

    /*
     * @route("/affichemobilemon",name="AfficheMobilemontage")
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface

    public function Affichemobilemontage(NormalizerInterface $normalizer,MontageRepository $repo): Response
    {
        $repo=$this->getDoctrine()->getRepository(Montage::class) ;
        $montage=$repo->findAll();
        $jsonContent=$normalizer->normalize($montage,'json',['groups'=>'post:read']);


        return new Response(json_encode($jsonContent));

        //return $this->render('montage/affiche.html.twig',['montage'=>json_encode($montage)]);


    }*/

    /**
     * @Route("/affichemobilemon")
     * @return Response
     */
    public function recupererMontage(): Response
    {
        $montage = $this->getDoctrine()->getRepository(Montage::class)->findAll();

        if (!$montage) {
            return new Response(null);
        }

        $jsonContent = null;
        $i = 0;
        foreach ($montage as $montage) {
            $jsonContent[$i]['idmontage'] = $montage->getId();
            $jsonContent[$i]['Processeur'] = $montage->getProcesseur();
            $jsonContent[$i]['CarteGraphique'] = $montage->getCarteGraphique();
            $jsonContent[$i]['CarteMere'] = $montage->getCarteMere();
            $jsonContent[$i]['DisqueSysteme'] = $montage->getDisqueSysteme();
            $jsonContent[$i]['Boitier'] = $montage->getBoitier();
            $jsonContent[$i]['StockageSupp'] = $montage->getStockageSupp();
            $i++;
        }
        $json = json_encode($jsonContent);
        return new Response($json);
    }
    /**
     * @Route("/deletemontage{id}", name="delete")
     */
    public function DeleteMontage($id, MontageRepository $repo)
    {
        $montage=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($montage);
        $em->flush();
        $this->addFlash('success', 'Montage supprimé');

        return $this->redirectToRoute('affiche');

    }
    /*
     * @route("/Deletemobilem",name="deletem")
     * @Method ("DELETE")


    public function deletemobilemontage(Request $request,  EntityManagerInterface $em){
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $Montage  = $em->getRepository(Montage::class)->find($id);
        if($Montage != null)
        {
            $em->remove($Montage);
            $em->flush();

            return new JsonResponse("Montage Deleted ");
        }
        return new JsonResponse("not found");

    }*/
    /**
     * @Route("/Deletemobilem")
     * @param Request $request
     * @return Response
     */
    public function supprimerMontage(Request $request): Response
    {
        $idMontage = (int)$request->get("idmontage");

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($this->getDoctrine()->getRepository(Montage::class)->find($idMontage));
        $manager->flush();

        return new Response("Suppression effectué");
    }

    /**
     * @Route ("/update/{id}", name="updatemontage")
     */
    public function UpdateMontage(Request $request, Montage $montage)
    {
        $form=$this->createForm(MontageType::class, $montage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('affiche');
        }
        return $this->render('montage/update.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /*
     * @Route("/addmontagee", name="add_montage")
     * @Method("GET")


    public function ajouterMontageAction(Request $request)
    {
        $montage = new Montage();
        $Processeur = $request->query->get("Processeur");
        $CarteGraphique = $request->query->get("CarteGraphique");
        $CarteMere = $request->query->get("CarteMere");

        $DisqueSysteme = $request->query->get("DisqueSysteme");
        $Boitier = $request->query->get("Boitier");
        $StockageSupp = $request->query->get("StockageSupp");
        $em = $this->getDoctrine()->getManager();

        $montage->setCarteGraphique($CarteGraphique);
        $montage->setProcesseur($Processeur);
        $montage->setCarteMere($CarteMere);
        $montage->setDisqueSysteme($DisqueSysteme);
        $montage->setBoitier($Boitier);
        $montage->setStockageSupp($StockageSupp);
        $montage->setEmail("arij.hajji@esprit.tn");
        $montage->setIduser("1");
        $montage->setMontant("0");
        $em->persist($montage);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($montage);
        return new JsonResponse($formatted);

    }*/


    /**
     * @Route("/addmontagee", name="add_montage")
     * @Method("GET")
     */
    public function ajouterMontage(Request $request): Response
    {
        $montage = new Montage();


        $Processeur = $request->query->get("Processeur");
        $CarteGraphique = $request->query->get("CarteGraphique");
        $CarteMere = $request->query->get("CarteMere");

        $DisqueSysteme = $request->query->get("DisqueSysteme");
        $Boitier = $request->query->get("Boitier");
        $StockageSupp = $request->query->get("StockageSupp");

        $manager = $this->getDoctrine()->getManager();

        $montage->setCarteGraphique($CarteGraphique);
        $montage->setProcesseur($Processeur);
        $montage->setCarteMere($CarteMere);
        $montage->setDisqueSysteme($DisqueSysteme);
        $montage->setBoitier($Boitier);
        $montage->setStockageSupp($StockageSupp);
        $montage->setEmail("arij.hajji@esprit.tn");
        $montage->setIduser("1");
        $montage->setMontant("0");


        $manager->persist($montage);
        $manager->flush();
        return new Response ("sucesss");
    }
}
