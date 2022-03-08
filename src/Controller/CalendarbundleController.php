<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Form\CalendarType;
use App\Repository\CalendarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/calendarbundle")
 */
class CalendarbundleController extends AbstractController
{
    /**
     * @Route("/", name="calendarbundle_index", methods={"GET"})
     */
    public function index(CalendarRepository $calendarRepository): Response
    {
        return $this->render('calendarbundle/index.html.twig', [
            'calendars' => $calendarRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="calendarbundle_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $calendar = new Calendar();
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($calendar);
            $entityManager->flush();

            return $this->redirectToRoute('calendarbundle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('calendarbundle/new.html.twig', [
            'calendar' => $calendar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="calendarbundle_show", methods={"GET"})
     */
    public function show(Calendar $calendar): Response
    {
        return $this->render('calendarbundle/show.html.twig', [
            'calendar' => $calendar,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="calendarbundle_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Calendar $calendar, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('calendarbundle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('calendarbundle/edit.html.twig', [
            'calendar' => $calendar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="calendarbundle_delete", methods={"POST"})
     */
    public function delete(Request $request, Calendar $calendar, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$calendar->getId(), $request->request->get('_token'))) {
            $entityManager->remove($calendar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('calendarbundle_index', [], Response::HTTP_SEE_OTHER);
    }
}
