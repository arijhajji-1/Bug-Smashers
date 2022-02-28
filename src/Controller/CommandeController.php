<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/commande")
 */
class CommandeController extends AbstractController
{
    /**
     * @Route("/", name="commande_index", methods={"GET"})
     */
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="commande_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {

        $commande = new Commande();
        $panier = $session->get("panier", []);
        $session->set("panier", $panier);
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($commande);
            $entityManager->flush();
            $session->remove("panier");
            $this->addFlash('success', 'VOTRE COMMANDE A ETE PASSE AVEC SUCCEE ET EN COUR DE LIVRAISON');

            return $this->redirectToRoute('commande_index', [],Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'panier'=>$panier,
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/listc", name="listec", methods={"GET"})
     */
    public function listc(CommandeRepository $commandeRepository,SessionInterface $session): Response
    {

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $commande = $commandeRepository->findAll();

        // Retrieve the HTML generated in our twig file
        $html= $this->renderView('commande/listec.html.twig', [
            'commandes' => $commande,

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
     * @Route("/{id}", name="commande_show", methods={"GET"})
     */
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }
    /**
     * @param CommandeRepository $repo
     * @return Response \Symfony\Component\HttpFoundation\Response
     * @Route("/commande/show1", name="Affiche")
     */
    public function show1(CommandeRepository $CommandeRepository): Response
    {
        return $this->render('commande/showB.html.twig', [
            'commandes' => $CommandeRepository->findAll(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="commande_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/editB", name="commande_editB", methods={"GET", "POST"})
     */
    public function editB(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('Affiche', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/editB.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="commande_delete", methods={"POST"})
     */
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commande->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commande_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("commande/recherche",name="recherche")
     *
     */
    function recherche(CommandeRepository $repository,Request $request){
        $data=$request->get('recherche');
        $commande=$repository->findBy(['nom'=>$data]);
        return $this->render('commande/showB.html.twig',['commandes'=>$commande]);
    }

    /**
     * @Route("/commande/newliste", name="commande_liste", methods={"GET", "POST"})
     */
    public function getcommande(SerializerInterface $serializerInterface,CommandeRepository $repo): Response
    {
        $commande=$repo->findAll();
        $json=$serializerInterface->serialize($commande,'json',['groups'=>'commande']);
        dump($json);
        die;


    }






}