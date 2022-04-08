<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use App\Form\ProductModifierType;
use App\Form\ProductType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categoryaffback", name="category")
     */
    public function back(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'Categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/listeC",name="listeC")
     */
    public function getCategories(CategoryRepository $repo,NormalizerInterface $ni){
        $category = $repo->findAll();
        $json = $ni->normalize($category,'json',['groups' => 'category']);
        return new Response(json_encode($json));
    }
    /**
     * @Route("/category/ajouter", name="category_ajouter")
     */
    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_ajouter', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/ajouter.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modify-category/{id}", name="modifier_categorie")
     */
    public function modifyCategorie(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();

            return $this->redirectToRoute('category', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render("category/modifiercat.html.twig", [
            "form" => $form->createView()
        ]);
    }
    /**
     * @Route("/supprimer_categorie/{id}", name="supprimer_categorie")
     */
    public function deleteCategorie(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categorie = $entityManager->getRepository(Category::class)->find($id);
        foreach ($categorie->getProduitLouer() as $element) {
            $categorie->removeProduitsLouer($element);
        }
        foreach ($categorie->getProduitAcheter() as $element) {
            $categorie->removeProduitAcheter($element);
        }
        $entityManager->remove($categorie);
        $entityManager->flush();

        return $this->redirectToRoute("category");
    }


}
