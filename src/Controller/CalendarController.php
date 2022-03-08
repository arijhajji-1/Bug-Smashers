<?php

namespace App\Controller;

use App\Repository\CalendarRepository;
use CalendarBundle\CalendarBundle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Calendar;
class CalendarController extends AbstractController
{
    /**
     * @Route("/calendar", name="calendar")
     */
    public function index(): Response
    {
        $events = $this->getDoctrine()->getRepository(Calendar::class)->findAll();
        $rdvs=[];
        foreach ($events as $event){

            $rdvs[]= [
                'id' => $event->getId(),
            'start' => $event->getStart()->format('y-m-d h:i:s'),
            'end' => $event->getEnd()->format('y-m-d h:i:s'),
            'title' => $event->getTitle(),
            'description' => $event->getDescription(),
            'backgroundColor' => $event->getBackgroundColor(),
            'borderColor' => $event->getBorderColor(),
            'textColor' => $event->getTextColor(),
                'allDay' => $event->getAllDay(),
            ];

        }
        $data = json_encode($rdvs);
        return $this->render('calendar/index.html.twig', compact('data')
           // ['controller_name' => 'CalendarController',]
        );
    }
}
