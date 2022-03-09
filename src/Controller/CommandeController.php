<?php

namespace App\Controller;
use App\Entity\Commande;
use App\Entity\User;
use App\Data\SearchDataCommande;
use App\Form\SearchCommandeForm;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\ProduitAcheterRepository;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Constraints\Json;
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
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
/**
 * @Route("/commande")
 */
class CommandeController extends AbstractController
{
    /**
     * @Route("/", name="commande_index", methods={"GET"})
     */
    public function index(CommandeRepository $CommandeRepository,Request $request, PaginatorInterface $paginator): Response
    {

        $donnees=$CommandeRepository->findAll();

        $commande= $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page

        );

        return $this->render('commande/index.html.twig',['commandes'=>$commande]);
    }



    /**
     * @Route("/new", name="commande_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,SessionInterface $session,\Swift_Mailer $mailer): Response
    {

        $commande = new Commande();
        $panier = $session->get("panier", []);
        $form = $this->createForm(CommandeType::class, $commande);
        $commande->setIduser($this->getUser()->getId());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->persist($commande);
            $entityManager->flush();
            $message = (new \Swift_Message( 'VOTRE COMMANDE A ETE PASSE AVEC SUCCEE ET EN COUR DE LIVRAISON!'))
                ->setFrom('Reloua.tunisie@gmail.com')

                ->setTo($this->getUser()->getEmail())
                ->setBody(

                    $this->renderView(
                        'mail/welcome.html.twig', compact('commande')
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);



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
     * @Route("/commande/info_commande", name="info_cart")
     */
    public function sh(SessionInterface $session,produitAcheterRepository $produitAcheterRepository)
    {
        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;


        foreach ($panier as $id => $quantite) {
            $TypeP = $produitAcheterRepository->findAll();
            $product = $produitAcheterRepository->find($id);
            $dataPanier[] = [
                "produitAcheter" => $product,
                "quantite" => $quantite,
                "Type" => $TypeP,
            ];
            $total += $product->getPrix()* $quantite;

        }
        return $this->render('commande/info_commande.html.twig', compact("dataPanier", "total"));
    }

    /**
     * @Route("/commande/add2", name="add_commande",methods={"GET", "POST"})
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */

    public function addcommande(Request $request)
    {
        $commande = new commande();
        $nom = $request->query->get("nom");
        $prenom = $request->query->get("prenom");
        $paiment = $request->query->get("paiment");

        $adresse = $request->query->get("adresse");
        $telephone = $request->query->get("telephone");

        $em = $this->getDoctrine()->getManager();

        $commande->setNom($nom);
        $commande->setPrenom($prenom);
        $commande->setPaiment($paiment);
        $commande->setAdresse($adresse);
        $commande->setTelephone($telephone);


        $em->persist($commande);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($commande);
        return new JsonResponse($formatted);

    }


    /**
     * @Route("/listc", name="listec", methods={"GET"})
     */
    public function listc(CommandeRepository $commandeRepository,SessionInterface $session,produitAcheterRepository $produitAcheterRepository): Response
    {

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $commande = $commandeRepository->findAll();
        $panier = $session->get("panier", []);
        $dataPanier = [];
        $total = 0;

        foreach ($panier as $id => $quantite) {
            $TypeP = $produitAcheterRepository->findAll();
            $product = $produitAcheterRepository->find($id);
            $dataPanier[] = [
                "produitAcheter" => $product,
                "quantite" => $quantite,
                "Type" => $TypeP,
            ];
            $total += $product->getPrix() * $quantite;

        }

        // Retrieve the HTML generated in our twig file
        $html= $this->renderView('commande/listec.html.twig', [
            'commandes' => $commande,
            'dataPanier' => $dataPanier,
            'panier'=>$panier,
            'total'=>$total,

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
     * @Route("/commande/liste",name="liste_commande",methods={"GET"})
     */
    public function getCommande(NormalizerInterface $normalizer,CommandeRepository $repo): Response
    {
        $repo=$this->getDoctrine()->getRepository(Commande::class) ;
        $commande=$repo->findAll();
        $jsonContent=$normalizer->normalize($commande,'json',['groups'=>'commande']);


        return new Response(json_encode($jsonContent));

    }

      // /**
     //* @param CommandeRepository $repo
     //* @return Response \Symfony\Component\HttpFoundation\Response
     //* @Route("/commande/show1", name="Affiche")
     //*/
    /* public function show1(CommandeRepository $CommandeRepository,Request $request, PaginatorInterface $paginator): Response
    {
        return $this->render('commande/showB.html.twig', [
            'commandes' => $CommandeRepository->findAll(),
        ]);
    }
    */


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

            return $this->redirectToRoute('commandeSearch', [], Response::HTTP_SEE_OTHER);
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
     * @Route("/deleteB/{id}", name="commande_deleteB", methods={"POST"})
     */
    public function d(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commande->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commandeSearch', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/commande/recherche",name="recherche")
     *
     */
    function recherche(CommandeRepository $repository,Request $request){
        $data=$request->get('recherche');
        $commande=$repository->findBy(['nom'=>$data]);
        return $this->render('commande/showB.html.twig',['commandes'=>$commande]);
    }



    /**
     * @Route("/commande/show1", name="commandeSearch")
     */
    public function chercherEvent(CommandeRepository $CommandeRepository, Request $request): Response
    {
        $data = new SearchDataCommande();
        $form = $this->createForm(SearchCommandeForm::class,$data, [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        $commandes=$CommandeRepository->findSearch($data);
        return $this->render('commande/showB.html.twig', [
            'commandes' => $commandes,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/commande/show1", name="searchEvent")
     */
    public function searchEvent(CommandeRepository $CommandeRepository, Request $request)
    {
        $data = new SearchDataCommande();
        $form = $this->createForm(SearchCommandeForm::class,$data, [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        if ($request->isXmlHttpRequest()) {
            $commande=$CommandeRepository->findSearch($data);
            foreach($commande as $item) {
                $arrayCollection[] = array(
                    'id' => $item->getId(),
                    'nom' => $item->getNom(),
                    'prenom' => $item->getPrenom(),
                    'paiment' => $item->getPaiment(),
                    'adresse' => $item->getAdresse(),
                    'telephone' => $item->getTelephone(),

                );
            }
            return new JsonResponse($arrayCollection);
        }
    }





}