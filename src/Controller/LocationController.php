<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use App\Entity\ProduitLouer;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/location")
 */
class LocationController extends AbstractController
{
    /**
     * @Route("/", name="location_index", methods={"GET"})
     */
    public function index(LocationRepository $locationRepository): Response
    {
        return $this->render('location/index.html.twig', [
            'locations' => $locationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="location_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location);
        $location->setIduser($this->getUser()->getId());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($location);
            $entityManager->flush();

            return $this->redirectToRoute('location_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('location/new.html.twig', [
            'location' => $location,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="location_show", methods={"GET"})
     */
    public function show(Location $location): Response
    {
        return $this->render('location/show.html.twig', [
            'location' => $location,
        ]);
    }

    /**
     * @param LocationRepository $repo
     * @return Response \Symfony\Component\HttpFoundation\Response
     * @Route("/location/showB", name="AfficheB")
     */
    public function showB(LocationRepository $locationRepository): Response
    {
        return $this->render('location/showB.html.twig', [
            'locations' => $locationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="location_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Location $location, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('location_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('location/edit.html.twig', [
            'location' => $location,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/editB", name="location_editB", methods={"GET", "POST"})
     */
    public function editB(Request $request, Location $location, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('AfficheB', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('location/editB.html.twig', [
            'location' => $location,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="location_delete", methods={"POST"})
     */
    public function delete(Request $request, Location $location, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$location->getId(), $request->request->get('_token'))) {
            $entityManager->remove($location);
            $entityManager->flush();
        }

        return $this->redirectToRoute('location_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/deleteB/{id}", name="location_deleteB", methods={"POST"})
     */
    public function d(Request $request, Location $location, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $location->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($location);
            $entityManager->flush();
        }

        return $this->redirectToRoute('AfficheB', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/location/add3", name="add_location")
     * @Method("GET")
     */

    public function addlocation(Request $request)
    {
        $location = new Location();
        $dateDEB = $request->query->get("dateDEB");
        $dateFin = $request->query->get("dateFin");
        $TotalL = $request->query->get("TotalL");
        $produit=$request->query->get("produit");



        $em = $this->getDoctrine()->getManager();

        $location->setDateDEB(new \DateTime());
        $location->setDateFin(new \DateTime());
        $location->setTotalL($TotalL);



        $location->setIduser("1");


        $em->persist($location);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($location);
        return new JsonResponse($formatted);

    }


    /**
     * @Route("/location/updatelocation/{id}", name="update_location")
     * @Method("PUT")
     */
    public function modifierlocationAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $location = $this->getDoctrine()->getManager()
            ->getRepository(Location::class)
            ->find($request->get("id"));
        $dateDEB = $request->query->get("dateDEB");
        $dateFin = $request->query->get("dateFin");
        $TotalL = $request->query->get("TotalL");

        $location->setDateDEB(new \DateTime());
        $location->setDateFin(new \DateTime());
        $location->setTotalL($TotalL);

        $em->persist($location);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($location);
        return new JsonResponse("location a ete modifiee avec success.");

    }

    /**
     * @Route("/location/deletelocation", name="delete_location")
     * @Method("DELETE")
     */

    public function deletelocationAction(Request $request) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $location = $em->getRepository(Location::class)->find($id);
        if($location!=null ) {
            $em->remove($location);
            $em->flush();
            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Votre location a ete supprimee avec success.");
            return new JsonResponse($formatted);
        }
        return new JsonResponse("id location invalide.");
    }
    /**
     * @Route("/location/liste2",name="liste_location")
     */
    public function getlocation(NormalizerInterface $normalizer,LocationRepository $repo): Response
    {
        $repo=$this->getDoctrine()->getRepository(Location::class) ;
        $location=$repo->findAll();
        $jsonContent=$normalizer->normalize($location,'json',['groups'=>'location']);
        return new Response(json_encode($jsonContent));
    }
}
