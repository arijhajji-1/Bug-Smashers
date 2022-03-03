<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchForm;
use App\Entity\Avis_Produit;
use App\Entity\Category;
use App\Form\AvisType;
use App\Entity\ProduitAcheter;
use App\Form\ProduitAcheterType;
use App\Entity\ProduitLouer;
use App\Form\ProduitLouerType;
use App\Repository\CategoryRepository;
use App\Repository\ProduitAcheterRepository;
use App\Repository\ProduitLouerRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Diff\DiffColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use App\Service\FileUploader;

class ProduitController extends AbstractController
{
    /**
     * @Route("/single-productA/{id}",name="single_produitA")
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     */
    public function singleProductA(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(ProduitAcheter::class)->find($id);
        $avis = new Avis_Produit();
        $avis->setProduitAcheter($produit);
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setEmail($this->getUser()->getEmail());
            $avis->setNom($this->getUser()->getFirstName());
            $entityManager->persist($avis);
            $entityManager->flush();
            $this->addFlash('success', 'Avis ajouté avec succés');
            return $this->redirectToRoute('single_produitA', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }
        return $this->render("produit/single-product-A.html.twig", [
            "produit" => $produit,
            "categorie" => $produit->getCategory()->getLabel(),
            'form' => $form->createView(),
            "avis" => $produit->getAvis(),
        ]);
    }
    /**
     * @Route("/single-productL/{id}",name="single_produitL")
     */
    public function singleProductL(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(ProduitLouer::class)->find($id);
        $avis = new Avis_Produit();
        $avis->setProduitLouer($produit);
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setEmail($this->getUser()->getEmail());
            $avis->setNom($this->getUser()->getFirstName());
            $entityManager->persist($avis);
            $entityManager->flush();
            $this->addFlash('success', 'Avis ajouté avec succés');
            return $this->redirectToRoute('single_produitL', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }
        return $this->render("produit/single-product-L.html.twig", [
            "produit" => $produit,
            "categorie" => $produit->getCategory()->getLabel(),
            'form' => $form->createView(),
            "avis" => $produit->getAvis(),
        ]);
    }
    /**
     * @Route("/invoices", name="acheter")
     */
    public function processAction(ProduitLouerRepository $produitLouerRepository,ProduitAcheterRepository $produitAcheterRepository,
                          CategoryRepository $categoryRepository,Request $request)
    {
        $data = new SearchData();
        $form = $this->createForm(SearchForm::class,$data, [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        if ($request->isXmlHttpRequest()) {

            $produitA=$produitAcheterRepository->findSearch($data);
            $produitL=$produitLouerRepository->findSearch($data);
            foreach($produitA as $item) {
                $arrayCollection[] = array(
                    'id' => $item->getId(),
                    'nom' => $item->getNom(),
                    'prix' => $item->getPrix(),
                    'marque' => $item->getMarque(),
                    'category' => $item->getCategory()->getLabel(),
                    'qte' => $item->getQte(),
                );
            }
            return new JsonResponse($arrayCollection);
        }

    }
    /**
     * @Route("/inv", name="louer")
     */
    public function louerAction(ProduitLouerRepository $produitLouerRepository,ProduitAcheterRepository $produitAcheterRepository,
                                  CategoryRepository $categoryRepository,Request $request)
    {
        $data = new SearchData();
        $form = $this->createForm(SearchForm::class,$data, [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        if ($request->isXmlHttpRequest()) {
            $produitL=$produitLouerRepository->findSearch($data);
            foreach($produitL as $item) {
                $arrayCollection[] = array(
                    'id' => $item->getId(),
                    'nom' => $item->getNom(),
                    'prix' => $item->getPrix(),
                    'marque' => $item->getMarque(),
                    'category' => $item->getCategory()->getLabel(),
                    'etat' => $item->getEtat(),
                    'dispo'=>$item->getDispo(),
                );
            }
            return new JsonResponse($arrayCollection);
        }

    }
    /**
     * @Route("/produit/afffront", name="produit_affichage_front")
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     */
    public function index(ProduitLouerRepository $produitLouerRepository,ProduitAcheterRepository $produitAcheterRepository,
                          CategoryRepository $categoryRepository,Request $request, PaginatorInterface $paginator): Response
    {
        $data = new SearchData();
        $form = $this->createForm(SearchForm::class,$data, [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        $produitA=$produitAcheterRepository->findSearch($data);
        $produitL=$produitLouerRepository->findSearch($data);
        $produitA = $paginator->paginate(
            $produitA, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        $produitL = $paginator->paginate(
            $produitL, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
        return $this->render('produit/index.html.twig', [
            'produitsAcheter' => $produitA,
            'produitsLouer' => $produitL,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/produit/afffront/{cat}", name="produit_affichage_front_cat")
     */
    public function affbyCat(int $cat, CategoryRepository $categoryRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Category::class)->find($cat);
        return $this->render('produit/index.html.twig', [
            'produitsAcheter' => $category->getProduitAcheter(),
            'produitsLouer' => $category->getProduitLouer(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/produit/ajouterA", name="produit_acheter_ajout")
     */
    public function ajouterA(Request $request, EntityManagerInterface $entityManager
        , FileUploader $fileUploader): Response
    {
        $product = new ProduitAcheter();
        $category = new Category();
        $form = $this->createForm(ProduitAcheterType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imagePath')->getData();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                $product->setImagePath($fileName);
            }
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('ajoutA', 'Produit ajouté avec succés');

            return $this->redirectToRoute('produit_acheter_ajout', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/ajouterA.html.twig', [
            'produit' => $product,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/produit/ajouterL", name="produit_louer_ajout")
     */
    public function ajouterL(Request $request, EntityManagerInterface $entityManager
        , FileUploader $fileUploader): Response
    {
        $product = new ProduitLouer();
        $category = new Category();
        $form = $this->createForm(ProduitLouerType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imagePath')->getData();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                $product->setImagePath($fileName);
            }
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('ajoutL', 'Produit ajouté avec succés');
            return $this->redirectToRoute('produit_louer_ajout', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/ajouterL.html.twig', [
            'produit' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/produit/affback/acheter", name="produit_acheter_affichage_back")
     */
    public function backAcheter(ProduitAcheterRepository $produitRepository, Request $request): Response
    {
        $data = new SearchData();
        $form = $this->createForm(SearchForm::class,$data, [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        $produitA=$produitRepository->findSearch($data);
        return $this->render('produit/affbackacheter.html.twig', [
            'products' => $produitA,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/produit/affback/louer", name="produit_Louer_affichage_back")
     */
    public function backLouer(ProduitLouerRepository $produitRepository, Request $request): Response
    {
        $data = new SearchData();
        $form = $this->createForm(SearchForm::class,$data, [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        $produitL=$produitRepository->findSearch($data);
        return $this->render('produit/affbacklouer.html.twig', [
            'products' => $produitL,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modify-productA/{id}", name="modifier_produitA")
     */
    public function modifyProductA(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(ProduitAcheter::class)->find($id);
        $form = $this->createForm(ProduitAcheterType::class, $produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
            $this->addFlash('modifA', 'Produit modifié avec succés');
            return $this->redirectToRoute('produit_acheter_affichage_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render("produit/modifierprodA.html.twig", [
            "form" => $form->createView()
        ]);
    }
    /**
     * @Route("/modify-productL/{id}", name="modifier_produitL")
     */
    public function modifyProductL(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(ProduitLouer::class)->find($id);
        $form = $this->createForm(ProduitLouerType::class, $produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
            $this->addFlash('modifL', 'Produit modifié avec succés');

            return $this->redirectToRoute('produit_Louer_affichage_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render("produit/modifierprodL.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/supprimer_produitA/{id}", name="supprimer_produitA")
     */
    public function deleteProductA(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(ProduitAcheter::class)->find($id);
        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute("produit_acheter_affichage_back");
    }
    /**
     * @Route("/supprimer_produitL/{id}", name="supprimer_produitL")
     */
    public function deleteProductL(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(ProduitLouer::class)->find($id);
        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute("produit_Louer_affichage_back");
    }
    /**
     * @Route("/produit/stats", name="stats")
     */
    public function charts(CategoryRepository $categoryRepository,ProduitLouerRepository $pl,
                           ProduitAcheterRepository $ProduitAcheterRepository)
    {
        $pieChartA = new PieChart();
        $pieChartL = new PieChart();
        $columnChartA = new ColumnChart();
        $columnChartL = new ColumnChart();
        $cat = $categoryRepository->findAll();
        $prodA = $ProduitAcheterRepository->findAll();
        $prodL = $pl->findAll();
        $arrayCollectionA[] = ['Categorie','Valeur'];
        $arrayCollectionL[] = ['Categorie','Valeur'];
        $arrayQtA[] = ['Produit','Quantité'];
        $arrayQtL[] = ['Produit','Nombres des produits à louer'];
        foreach($cat as $item) {
            $arrayCollectionA[] = array(  $item-> getLabel(),
                count($item->getProduitAcheter()));
            $arrayCollectionL[] = array(  $item-> getLabel(),
                count($item->getProduitLouer()));
        }
        foreach($prodA as $item){
            $arrayQtA[] = array(  $item-> getNom(),
                $item->getQte());
        }
        $l=0;
        $tot = count($prodL);
        foreach($prodL as $item){
            if($item->getDispo()){
                $l++;
            }
        }
        $oldColumnChart = new ColumnChart();
        $oldColumnChart->getData()->setArrayToDataTable([['Produit', 'Tous les produits'],
            ['Tous les produits', $tot]]);
        $newColumnChart = new ColumnChart();
        $newColumnChart->getData()->setArrayToDataTable([['Produit', 'Nombres des produits à louer'],
            ['Loué', $l]]);
        $pieChartA->getData()->setArrayToDataTable($arrayCollectionA);
        $pieChartA->getOptions()->setTitle('Produits Acheter Par Categorie');
        $pieChartA->getOptions()->setHeight(500);
        $pieChartA->getOptions()->setWidth(900);
        $pieChartA->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChartA->getOptions()->getTitleTextStyle()->setColor('#121212');
        $pieChartA->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChartA->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChartA->getOptions()->getTitleTextStyle()->setFontSize(20);
        $pieChartA->getOptions()->setSliceVisibilityThreshold(0);

        //**********************************************************
        $pieChartL->getData()->setArrayToDataTable($arrayCollectionL);
        $pieChartL->getOptions()->setTitle('Produits Louer Par Categorie');
        $pieChartL->getOptions()->getLegend();
        $pieChartL->getOptions()->setHeight(500);
        $pieChartL->getOptions()->setWidth(900);
        $pieChartL->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChartL->getOptions()->getTitleTextStyle()->setColor('#121212');
        $pieChartL->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChartL->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChartL->getOptions()->getTitleTextStyle()->setFontSize(20);
        $pieChartL->getOptions()->setSliceVisibilityThreshold(0);

        //********************************************************************

        $columnChartA->getData()->setArrayToDataTable($arrayQtA);
        $columnChartA->getOptions()->getLegend()->setPosition('top');
        $columnChartA->getOptions()->setWidth(900);
        $columnChartA->getOptions()->setHeight(500);

        //********************************************************************

        $columnChartL->getData()->setArrayToDataTable($arrayQtA);
        $columnChartL->getOptions()->getLegend()->setPosition('top');
        $columnChartL->getOptions()->setWidth(900);
        $columnChartL->getOptions()->setHeight(500);
        //**********************************************************
        $newColumnChart->setOptions($oldColumnChart->getOptions());

        $diffColumnChart = new DiffColumnChart($oldColumnChart, $newColumnChart);
        $diffColumnChart->getOptions()->getLegend()->setPosition('top');
        $diffColumnChart->getOptions()->setWidth(900);
        $diffColumnChart->getOptions()->setHeight(500);
        $diffColumnChart->getOptions()->getDiff()->getNewData()->setWidthFactor(0.5);
        return $this->render('produit/affbackcharts.html.twig', array(
            'produitAcheterChart' => $pieChartA,
            'produitLouerChart' => $pieChartL,
            'qteA' =>$columnChartA,
            'qteL' =>$diffColumnChart,
        ));
    }

}
