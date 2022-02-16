<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use App\Form\ProductModifierType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\FileUploader;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit/afffront", name="produit_affichage_front")
     */
    public function index(ProductRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/produit/ajouter", name="produit_ajout")
     */
    public function ajouter(Request $request, EntityManagerInterface $entityManager
        , FileUploader $fileUploader): Response
    {
        $product = new Product();
        $category = new Category();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imagePath')->getData();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                $product->setImagePath($fileName);
            }
                        $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('produit_ajout', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/ajouter.html.twig', [
            'produit' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/produit/affback", name="produit_affichage_back")
     */
    public function back(ProductRepository $produitRepository): Response
    {
        return $this->render('produit/affback.html.twig', [
            'products' => $produitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/modify-product/{id}", name="modifier_produit")
     */
    public function modifyProduct(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $produit = $entityManager->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductModifierType::class, $produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();

            return $this->redirectToRoute('produit_affichage_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render("produit/modifierprod.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/supprimer_produit/{id}", name="supprimer_produit")
     */
    public function deleteProduct(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(Product::class)->find($id);
        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute("produit_affichage_back");
    }

}
