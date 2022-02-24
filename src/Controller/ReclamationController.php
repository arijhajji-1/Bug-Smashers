<?php

namespace App\Controller;

use App\Form\ReclamationType;
//use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reclamation;
use App\Repository\ReclamationRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Validator\Constraints as Assert;

class ReclamationController extends AbstractController
{



    /**
     * @Route("/reclamation/add", name="ajout_reclamation")
     */
//fonction de l'utilisateur pour ajouter une réclamation
    public function addrecl(Request $request){

        //créer une nouvelle réclamation
        $reclaim= new Reclamation();
        // Cette date sera donc préaffichée dans le formulaire, cela facilite le travail de l'utilisateur
        $reclaim->setDate(new \Datetime());
        //recuperer le formulaire
        $formBuilder = $this->createFormBuilder($reclaim);
        $formBuilder
            ->add('code',IntegerType::class)
            ->add('idCommande',IntegerType::class)
            ->add('categorie',ChoiceType::class,
                ['choices'  => [
                    'Location' => "Location",
                    'Montage' => "Montage",
                    'Reparation' => "Reparation",],
                ])
            ->add('sujet',TextType::class)
            ->add('description',TextareaType::class)
            ->add('date',DateType::class);
        //$form= $this->createForm(ReclamationType::class,$reclaim);
        //ajout d'un bouton submit
        //$form->add('ajouter', SubmitType::class);


        $form= $formBuilder->getForm();
        /*$request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
        }*/
        $form->handleRequest($request);
        //controle de saisie
        if($form->isSubmitted()&& $form->isValid())
        {
        $em=$this->getDoctrine()->getManager();
        $em->persist($reclaim);
        $em->flush();

            $this->addFlash('success', 'relamation enregistrée !');
        return $this->redirectToRoute('ajout_reclamation');
        }
        //rendre la vue et generer le html du formulaire
        return $this->render('reclamation/reclamation.html.twig',[
            'form'=>$form->createView()]);

    }






     /**
      * @return \Symfony\Component\HttpFoundation\Response
      * @Route("/reclamation/list", name="list_reclamation")
      */

     //fonction de l'admnistrateur pour afficher la liste des réclamations
    public function reclaimlist()
    {

        $reclamations = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        return $this->render('reclamation/listeReclamation.html.twig', ['reclamations' => $reclamations]);
        if (!$reclamations) {
            throw $this->createNotFoundException(
                'Aucune reclamation trouvée dans la base de données'
            );
        }
    }








    /**
     * @Route ("/update/{id}", name="update")
     */
    //fonction de l'utilisateur pour modifier sa réclamation
    public function UpdateReclamation(ReclamationRepository $repo,$id,Request $request)
    {
        $reclamation = $repo->findOneByCode($id);
        // Et on construit le formBuilder avec cette instance de reclamation
        $formBuilder = $this->createFormBuilder($reclamation);
        $formBuilder
            ->add('code',IntegerType::class)
            ->add('idCommande',IntegerType::class)
            ->add('categorie',ChoiceType::class,
                ['choices'  => [
                    'Location' => "Location",
                    'Montage' => "Montage",
                    'Reparation' => "Reparation",],
                ])
            ->add('sujet',TextType::class)
            ->add('description',TextareaType::class)
            ->add('date',DateType::class);
       // $form = $this->createForm(ReclamationType::class, $reclamation);
       // $form->add('update', SubmitType::class);
        $form= $formBuilder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('list_reclamation');
        }
        return $this->render('reclamation/update.html.twig', [
            'form' => $form->createView()
        ]);
    }






    /**
     * @Route("/reclamation/delete/{id}", name="delete_reclamation")
     */

    //fonction de l'administrateur pour supprimer une réclamation
 public function delete ( $id,ReclamationRepository $repository)
 {
     $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->findOneByCode($id);
   /* dump($reclamation);
    die();*/
     $em=$this->getDoctrine()->getManager();

     $em->remove($reclamation);
     $em->flush();
     return $this->redirectToRoute('list_reclamation');

 }
































































}
