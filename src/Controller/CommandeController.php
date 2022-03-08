<?php

namespace App\Controller;

use App\Entity\Commande;

use App\Entity\Livraison;
use App\Form\CommandeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class CommandeController extends AbstractController
{
    /**
     * @Route("/commande", name="commande")
     */
    public function index(): Response
    {
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }

    /**
     * @Route("/ajout", name="Ajout_commande")
     *
     * @param Commande $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
         public function addAction ( Request $request )
      {
    //Création d'un produit
        $commande = new Commande ();
    //Récupération du formulaire
       $form = $this ->createForm (CommandeType::class,$commande );
    //Lien Objet géré par le formulaire -> Requête soumission du formulaire
      $form->handleRequest ($request);
    //si le formulaire a été soumis et est valide
     if ($form->isSubmitted() && $form->isValid ()){
         $uploadedFile = $form['photo']->getData();
         $destination = $this->getParameter('kernel.project_dir').'/public/upload';
         $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
         $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
         $uploadedFile->move($destination, $newFilename);
         $commande->setPhoto($newFilename);

    //enregistrement du produit dans la bdd
    $em= $this->getDoctrine()->getManager();
    $em->persist($commande);
    $em->flush ();
    return  new  Response ( "Le produit à bien été ajouté " );
   }
//Génération du code HTML pour le formulaire créé
     $formView = $form->createView ();
        //Affichage de la vue
             return  $this -> render ( 'commande/commande.html.twig' , array ('form' => $formView ));

    }

    /**
     * @Route("/list/commande", name="liste_commande")
     *
     */

public function listCommande(){
    $commande = $this->getDoctrine()->getRepository(Commande::class)->findAll();
    return $this->render('commande/listeCommande.html.twig', ['commande' => $commande]);
    if (!$reclamations) {
        throw $this->createNotFoundException(
            'Aucune reclamation trouvée dans la base de données'
        );
    }
}




}
