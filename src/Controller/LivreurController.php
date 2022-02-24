<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Livreur;
use App\Form\ClientType;
use App\Form\LivreurType;
use App\Repository\LivreurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LivreurController extends AbstractController
{
    /**
     * @Route("/livreur", name="livreur")
     */
    public function index(): Response
    {
        return $this->render('livreur/index.html.twig', [
            'controller_name' => 'LivreurController',
        ]);
    }

    /**
     * @Route("/livreur/add", name="ajout_livreur")
     */
    //fonction de l'administrateur pour ajouter un livreur
    public function addlivreur(Request $request){

        //créer une nouvelle réclamation
        $livreur= new Livreur();

        //recuperer le formulaire
        $form= $this->createForm(LivreurType::class,$livreur);
        //ajout d'un bouton submit
        //$form->add('ajouter', SubmitType::class);

        $form->handleRequest($request);
        //controle de saisie
        if($form->isSubmitted()&& $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($livreur);
            $em->flush();
            return $this->redirectToRoute('ajout_livreur');
        }
        //rendre la vue et generer le html du formulaire
        return $this->render('livreur/livreur.html.twig',[
            'form'=>$form->createView()]);

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/livreur/list", name="list_livreur")
     */

    //fonction de l'admnistrateur pour afficher la liste des livreurs
    public function livreurlist()
    {

        $livreur = $this->getDoctrine()->getRepository(Livreur::class)->findAll();
        return $this->render('livreur/listelivreur.html.twig', ['livreur' => $livreur]);
        if (!$livreur) {
            throw $this->createNotFoundException(
                'Aucune reclamation trouvée dans la base de données'
            );
        }
    }


    /**
     * @Route ("/update_livreur/{id}", name="update_livreur")
     */
    //fonction de l'administrateur pour modifier un livreur
    public function Updatelivreur(LivreurRepository $repo,$id,Request $request)
    {
        $livreur = $repo->findOneByid($id);
        // Et on construit le formBuilder avec  cette instance de reclamation
        $form = $this->createForm(LivreurType::class, $livreur);
        // $form->add('update', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('list_livreur');
        }
        return $this->render('livreur/updateLivreur.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/livreur/delete/{id}", name="delete_livreur")
     */

    //fonction de l'administrateur pour supprimer un livreur
    public function deleteLivreur ( $id,LivreurRepository $repository)
    {
        $livreur = $this->getDoctrine()->getRepository(Livreur::class)->findOneByid($id);
        /* dump($reclamation);
         die();*/
        $em=$this->getDoctrine()->getManager();

        $em->remove($livreur);
        $em->flush();
        return $this->redirectToRoute('list_livreur');

    }







}
