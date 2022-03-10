<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Livraison;
use App\Form\Livraison1Type;
use App\Repository\LivraisonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Dompdf\Dompdf;
use Dompdf\Options;
/**
 * @Route("/livraison")
 */
class LivraisonController extends AbstractController
{
    /**
     * @Route("/list/livraison", name="livraison_index", methods={"GET"})
     */
    public function index(LivraisonRepository $livraisonRepository): Response
    {
        $livraison = $this->getDoctrine()->getRepository(Livraison::class)->findAll();
        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraison
        ]);
       /* return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisonRepository->findAll(),
        ]);*/
    }

    /**
     * @Route("/add/livraison/{id}", name="livraison_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        $livraison = new Livraison();
        $livraison->setDate(new \Datetime());

        $form = $this->createForm(Livraison1Type::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livraison);
            $entityManager->flush();
            $this->addFlash('success', 'livraison enregistrée!');
            return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);

        }
        /*$endDate =$livraison->getDate();
        $startDate =new \DateTime();
        $difference = $endDate->diff($startDate);
        $totalDaysDiff = $difference->format("%a");*/
        return $this->render('livraison/new.html.twig', [
           'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("/list/left/{id}", name="livraison_left", methods={"GET"})
     */
    public function leftdays(Livraison $livraison):Response
    {
        $endDate =$livraison->getDate();
        $startDate =new \DateTime();
        $difference = $endDate->diff($startDate);
        $totalDaysDiff = $difference->format("%a");
        return $this->render('livraison/left_days.html.twig', [
            "livraison" => $livraison,
            'jours' => $totalDaysDiff
        ]);

    }

    /**
     * @Route("/list/{id}", name="livraison_show", methods={"GET"})
     */
    public function show(Livraison $livraison): Response
    {
        return $this->render('livraison/show.html.twig', [
            'livraison' => $livraison,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="livraison_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Livraison $livraison, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(Livraison1Type::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'livraison modifiée !');
            return $this->redirectToRoute('livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livraison/edit.html.twig', [
            'livraison' => $livraison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="livraison_delete", methods={"POST"})
     */
    public function delete(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livraison->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livraison);
            $entityManager->flush();
            $this->addFlash('success', 'livraison supprimée !');
        }

        return $this->redirectToRoute('livraison_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @route("/pdf/livraison", name="livraisonpdf")
     */

    public function importPdf()
    {
        $livraison = $this->getDoctrine()->getRepository(Livraison::class)->findAll();
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('livraison/livraisonpdf.html.twig', [
            'title' => "liste des livraissons à faire",
            'livraisons' => $livraison
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("livraisonpdf.pdf", [
            "Attachment" => false
        ]);
    }

    /**
     * @route("/stat/livraison", name="stat_livraison")
     *
     */

    public function statLivraison(LivraisonRepository $repo)
    {
//chercher toutes les reclamations
        $livraisons=$repo->countday();
        $dates=[];
        $reclcount=[];
        foreach ($livraisons as $livraison)
        {
            $dates[]= $livraison['date'];
            $reclcount[]=$livraison['count'];

        }

        return $this->render('livraison/statistiqueLivraison.html.twig',
            [
                'dates'=>json_encode($dates),
                'reclcount'=>json_encode($reclcount),
            ]);

    }


























}
