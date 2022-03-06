<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Form\FactureType;
use App\Repository\CommandeRepository;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


/**
 * @Route("/facture")
 */
class FactureController extends AbstractController
{
    /**
     * @Route("/", name="facture_index", methods={"GET"})
     */
    public function index(FactureRepository $factureRepository): Response
    {
        return $this->render('facture/index.html.twig', [
            'factures' => $factureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/listf", name="listef", methods={"GET"})
     */
    public function listf(FactureRepository $factureRepository): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $facture = $factureRepository->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('facture/listef.html.twig', [
            'factures' => $facture,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);

    }

    /**
     * @Route("/new", name="facture_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param $factureRepository $repo
     * @return Response \Symfony\Component\HttpFoundation\Response
     * @Route("/facture/show", name="Facture_show")
     */
    public function show1(FactureRepository $factureRepository): Response
    {
        return $this->render('facture/show.html.twig', [
            'facture' => $factureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="facture_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="facture_delete")
     */
    public function delete(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $entityManager->remove($facture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('facture_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("facture/recherche",name="rechercheB")
     */
    function recherche(FactureRepository $repository,Request $request){
        $data=$request->get('rechercheB');
        $facture=$repository->findBy(['id'=>$data]);
        return $this->render('facture/index.html.twig',['factures'=>$facture]);
    }

    /**
     * @Route("/affichemobileFact",name="AfficheMobileFact")
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function affichemobileFact(NormalizerInterface $normalizer,FactureRepository $repo): Response
    {
        $repo=$this->getDoctrine()->getRepository(Facture::class) ;
        $facture=$repo->findAll();
        $jsonContent=$normalizer->normalize($facture,'json',['groups'=>'post:read']);


        return new Response(json_encode($jsonContent));

        //return $this->render('Reparation/afficherep.html.twig',['reparation'=>json_encode($reparation)]);


    }
}
