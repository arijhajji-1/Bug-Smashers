<?php

namespace App\Controller;
use App\Entity\AvisReparation;
use App\Entity\Reparation;
use App\Form\EtatType;
use App\Message\GenerateReport;
use App\Repository\AvisReparationRepository;
use App\Repository\ReparationRepository;
use \Symfony\Bundle\MonologBundle\SwiftMailer;

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

    /**
     * @route("/affichemobile",name="AfficheMobile")
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function Affichemobile(NormalizerInterface $normalizer,MontageRepository $repo): Response
    {
        $repo=$this->getDoctrine()->getRepository(Montage::class) ;
        $montage=$repo->findAll();
        $jsonContent=$normalizer->normalize($montage,'json',['groups'=>'post:read']);


        return new Response(json_encode($jsonContent));

        //return $this->render('montage/affiche.html.twig',['montage'=>json_encode($montage)]);


    }

    /**
     * @Route("/delete{id}", name="delete")
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
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @route("/Deletemobile/{id}",name="deletem")
     */

    public function deletemobile($id,MontageRepository $repository){
        $montage=$repository->find($id);
        $em = $this->getDoctrine()->getManager() ;
        $em -> remove($montage);
        $em -> flush();
        return  new Response("montage supprimé");

    }
    /**
     * @Route ("/update/{id}", name="update")
     */
    public function UpdateMontage(MontageRepository $repo,$id,Request $request)
    {
        $montage=$repo->find($id);
        $form=$this->createForm(MontageType::class,$montage);
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

    /**
     * @Route("/addmontage", name="add_montage")
     * @Method("POST")
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */

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

        $em->persist($montage);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($montage);
        return new JsonResponse($formatted);

    }


}
