<?php

namespace App\Controller;



use App\Entity\Evenement;

use App\Form\AvisEType;
use App\Form\EvenementType;
use App\Form\AvisType;

use App\Repository\AvisRepository;
use App\Repository\EvenementRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Avis;
use Swift_Mailer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class EvenementController extends AbstractController
{
    /**
     * @Route("/evenement", name="evenement")
     */
    public function index(): Response
    {
        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }

    /**
     * @Route("/evenement/ajouter", name="evenement_ajout")
     */
    public function ajouter(Request $request, EntityManagerInterface $entityManager
        , FileUploader $fileUploader,Swift_Mailer $mailer): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imageName')->getData();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                $evenement->setImageName($fileName);
            }
            $entityManager->persist($evenement);
            $this->mail($mailer);
            $entityManager->flush();

            return $this->redirectToRoute('evenement_ajout', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/ajouter.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/evenement/affiche", name="evenement_affiche")
     */
    public function affiche(EvenementRepository $repo){

        $repo=$this->getDoctrine()->getRepository(Evenement::class);
        $evenement=$repo->findAll();
        return $this->render('/evenement/affiche.html.twig',[
            'evenements'=>$evenement
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="evenement_supprimer")
     */
    public function SupprimerEvenement($id, EvenementRepository $repo,Swift_Mailer $mailer)
    {
        $evenement=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($evenement);
        $this->mailSupp($mailer);
        $em->flush();
        return $this->redirectToRoute('evenement_affiche');
    }
    /**
     * @Route ("/modifier/{id}", name="evenement_modifier")
     */
    public function ModifierEvenement(EvenementRepository $repo,$id,Request $request)
    {
        $evenement=$repo->find($id);
        $form=$this->createForm(EvenementType::class,$evenement);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('evenement_affiche');
        }
        return $this->render('evenement/modifier.html.twig',[
            'form'=>$form->createView()
        ]);

    }
    /**
     * @Route("/evenement/event/{id}",name="evenement_event")
     */
    public function evenementEvent(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $evenement = $entityManager->getRepository(Evenement::class)->find($id);
        $avis = new Avis();
        $avis->setEvenement($evenement);
        $form = $this->createForm(AvisEType::class, $avis);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setEvenement($evenement);
            $avis->setEmail($this->getUser()->getEmail());
            $avis->setNom($this->getUser()->getFirstName());
            $entityManager->persist($avis);
            $entityManager->flush();
            $this->addFlash('success','commentaire ajouté avec succès');

            return $this->redirectToRoute('evenement_event', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }
        $endDate =$evenement->getDate();
        $startDate =new \DateTime();
        $difference = $endDate->diff($startDate);
        $totalDaysDiff = $difference->format("%a");
        return $this->render("evenement/event.html.twig", [
            "evenement" => $evenement,
            'form' => $form->createView(),
            'avis' => $evenement->getAvis(),
            'jours' => $totalDaysDiff
        ]);
    }

    /**
     * @Route("/evenement/comment/{id}", name="evenement_comment")
     */
    public function Comment(AvisRepository $repo, $id){

        $repo=$this->getDoctrine()->getRepository(Evenement::class);
        $avis=$repo->find($id)->getAvis();
        return $this->render('/evenement/comment.html.twig',[
            'avis'=>$avis
        ]);
    }



    /**
     * @Route("/supprimer/comment/{id}", name="comment_supprimer")
     */
    public function SupprimerComment($id, AvisRepository $repo)
    {
        $avis=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($avis);
        $em->flush();
        return $this->redirectToRoute('evenement_comment');
    }




    public function mail( \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Vous Avez Ajouter un évenement!!  '))
            ->setFrom('Reloua.tunisie@gmail.com')
            ->setTo('nourelhoudamakkari@gmail.com')
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'emails/registration.html.twig'

                ),
                'text/html'

            );
        $mailer->send($message);
        // you can remove the following code if you don't define a text version for your emails
        //->addPart(
        //$this->renderView(
        // templates/emails/registration.txt.twig
        // 'emails/registration.txt.twig',

        //),
        // 'text/plain'

        ;

        $mailer->send($message);
    }

    public function mailSupp( \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('un évenement  est annulé!!  '))
            ->setFrom('nourelhoudamakkari@gmail.com')
            ->setTo('nourelhoudamakkari@gmail.com')
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'emails/registration.html.twig'

                ),
                'text/html'

            );
        $mailer->send($message);
        // you can remove the following code if you don't define a text version for your emails
        //->addPart(
        //$this->renderView(
        // templates/emails/registration.txt.twig
        // 'emails/registration.txt.twig',

        //),
        // 'text/plain'

        ;

        $mailer->send($message);
    }


    /**
     * @Route("/listeEvenement",name="listeEvenement")
     */
    public function listeEvenement(NormalizerInterface $normalizer)
    {
        $repo= $this->getDoctrine()->getRepository(evenement::class);
        $evenement = $repo->findAll();
        $jsonContent = $normalizer->normalize($evenement, 'json',['groups'=>'post:read']);


        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("/ajoutEvenement",name="ajoutEvenementJSON")
     */
    public function ajoutEvenementJSON(Request $request,NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $evenement = new Evenement();
        $evenement->setNom($request->get('nom'));
        $evenement->setDate(new \DateTime());
        $evenement->setHeure("13:00");
        $evenement->setDescription($request->get('description'));
        $evenement->setimageName($request->get('imageName'));
        $em->persist($evenement);
        $em->flush();
        $jsonContent = $normalizer->normalize($evenement, 'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/updateEvenement",name="updateEvenementJSON")
     */
    public function updateEvenementJSON(Request $request,NormalizerInterface $normalizer){
        $em = $this->getDoctrine()->getManager();
        $evenement = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->find($request->get("id"));
        $evenement->setNom($request->get('nom'));
        $evenement->setDate(new \DateTime());
        $evenement->setHeure("12");
        $evenement->setDescription($request->get('description'));
        $evenement->setimageName($request->get('imageName'));
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        return new Response("evenement updated successfully");

    }


    /**
     * @Route("/deleteEvenement",name="deleteEvenementJSON")
     * @Method ("DELETE")
     */
    public function deleteEvenement(Request $request,  EntityManagerInterface $em)
    {
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $Evenement = $em->getRepository(Evenement::class)->find($id);
        if($Evenement != null) {
            $em->remove($Evenement);
            $em->flush();

            return new JsonResponse("Evenement Deleted ");
        }
        return new JsonResponse("not found");
    }




}
