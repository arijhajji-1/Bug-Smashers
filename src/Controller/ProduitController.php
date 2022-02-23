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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\FileUploader;

class ProduitController extends AbstractController
{

    /**
     * @Route("/single-productA/{id}",name="single_produitA")
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
            $entityManager->persist($avis);
            $entityManager->flush();
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
            $entityManager->persist($avis);
            $entityManager->flush();
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
     * @Route("/produit/afffront", name="produit_affichage_front")
     */
    public function index(ProduitLouerRepository $produitLouerRepository,ProduitAcheterRepository $produitAcheterRepository,
                          CategoryRepository $categoryRepository,Request $request): Response
    {
        $data = new SearchData();
        $form = $this->createForm(SearchForm::class,$data, [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        $produitA=$produitAcheterRepository->findSearch($data);
        $produitL=$produitLouerRepository->findSearch($data);
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
    public function backAcheter(ProduitAcheterRepository $produitRepository): Response
    {
        return $this->render('produit/affbackacheter.html.twig', [
            'products' => $produitRepository->findAll(),
        ]);
    }
    /**
     * @Route("/produit/affback/louer", name="produit_Louer_affichage_back")
     */
    public function backLouer(ProduitLouerRepository $produitRepository): Response
    {
        return $this->render('produit/affbacklouer.html.twig', [
            'products' => $produitRepository->findAll(),
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

}
