<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index(): Response
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }

    /**
     * @route("/reclamation/search")
     */
    public function searchReclamation(Request $request, ReclamationRepository $repo)
    {
        $searchre = new Reclamation();
        // Cette date sera donc préaffichée dans le formulaire, cela facilite le travail de l'utilisateur
        $searchre->setDate(new \Datetime());
        $formBuilder = $this->createFormBuilder($searchre);
        $formBuilder
            ->add('categorie',
                ChoiceType::class,
                ['choices' => [
                    'Location' => "Location",
                    'Montage' => "Montage",
                    'Reparation' => "Reparation",],
                ])
            ->add('date', DateType::class);
        $form = $formBuilder->getForm();
        //$form->handleRequest($request);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {


        }
        return $this->render('search/reclamation.html.twig', [
            //'reclamations' => $r,
            'form' => $form->createView()]);

    }

    /**
     * @param Request $request
     * @return Response
     * @route ("/search/reclamation", name="search_reclamation")
     */

    public function rechercre(Request $request)
    {
        $propertySearch = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $propertySearch);
        $form->handleRequest($request);

        $reclamation = [];

        if ($form->isSubmitted() && $form->isValid()) {
            //on récupère le nom d'article tapé dans le formulaire Atelier framework Web côté serveur Symfony4

            $categorie = $propertySearch->getCategorie();
            if ($categorie != "")
                $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->findBy(['categorie' => $categorie]
                );
            else
//si si aucun nom n'est fourni on affiche tous les articles
                $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();

        }
        return $this->render('search/reclamation.html.twig', [
            'form' => $form->createView(),
            'reclamations' => $reclamation]);
    }


}


