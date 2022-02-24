<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Entity\Livreur;
use App\Form\LivraisonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class LivraisonController extends AbstractController
{
    /**
     * @Route("/livraison", name="livraison")
     */
    public function index(): Response
    {
        return $this->render('livraison/index.html.twig', [
            'controller_name' => 'LivraisonController',
        ]);
    }


    /**
     * @Route("/livraison/add", name="ajout_livraison")
     */
//fonction de l'utilisateur pour ajouter une réclamation
    public function addlivr(Request $request){

        //créer une nouvelle réclamation
        $livraison= new Livraison();
        // Cette date sera donc préaffichée dans le formulaire, cela facilite le travail de l'utilisateur
       // $reclaim->setDate(new \Datetime());
        //recuperer le formulaire
        $form= $this->createForm(LivraisonType::class,$livraison);
        //ajout d'un bouton submit
        //$form->add('ajouter', SubmitType::class);

        $form->handleRequest($request);
        //controle de saisie
        if($form->isSubmitted()&& $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($livraison);
            $em->flush();
            return $this->redirectToRoute('ajout_livraison');
        }
        //rendre la vue et generer le html du formulaire
        return $this->render('livraison/livraison.html.twig',[
            'form'=>$form->createView()]);

    }

    /**
     * @Route("/livraison/infos", name="infos_livraison")
     */
public function addreponse(Request $request){


    $livraison = new livraison();
    $formBuilder = $this->createFormBuilder($livraison);
    $formBuilder
        ->add('region',
            ChoiceType::class,
            ['choices'  => [
                'ariana' => "ariana",
                'tunis' => "tunis",
                'el aouina' => "el aouina",
                'la marsa' => "la marsa",
                'lac1' => "lac1",
                'menzah 5' => "menzah 5",],
            ])
        ->add('modpaie',ChoiceType::class,
            ['choices'  => [
                'cash à la livraison' => "cash à la livraison",
                'par carte' => "par carte",],
                'expanded'=> true,
            ])
        ->add('modlivr',ChoiceType::class,
            ['choices'  => [
                'à domicile' => "à domicile",
                'dans un point relai' => "dans un point relai",],
                'expanded'=> true,
            ]);
    $form= $formBuilder->getForm();
    $form->handleRequest($request);

    $livraison->setdescription("votre comande a bien été reçu!! vous serez
     livré dans 48h (ou vous pouvez passer retirer dans un de nos points relais)");
    // Cette date sera donc préaffichée dans le formulaire, cela facilite le travail de l'utilisateur
     $livraison->setDate(new \Datetime());
    $livreur = new livreur();

    $livraison->addLivreur($livreur);
    if($form->isSubmitted()&& $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($livreur);
        $entityManager->persist($livraison);
        $entityManager->flush();
       // return $this->redirectToRoute('infos_livraison');
    }

    return $this->render('livraison/livraison.html.twig',[
        'form'=>$form->createView()]);


}




























}
