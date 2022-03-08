<?php

namespace App\Controller;

use Date;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EvenementRepository;
use App\Entity\Evenement;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @Route("/api/{id}/edit", name="app_api_edit", methods={"PUT"})
     * @throws Exception
     */
    public function majEvent(?Evenement $calendar, Request $request): Response
    {
        $donnees = json_decode($request->getContent());

        if(

            isset($donnees->date) && !empty($donnees->date)

        ){

            $code = 200;

            if(!$calendar){

                $calendar = new Evenement;

                $code = 201;
            }
            $calendar->setNom($donnees->nom);
            $calendar->setDate(new Date ($donnees->date));
            $em = $this->getDoctrine()->getManager();
            $em->persist($calendar);
            $em->flush();

            // On retourne le code
            return new Response('Ok', $code);
        }else{
            // Les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }



        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}