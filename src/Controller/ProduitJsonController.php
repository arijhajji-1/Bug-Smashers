<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\ProduitAcheter;
use App\Form\ProduitAcheterType;
use App\Repository\ProduitAcheterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProduitJsonController extends AbstractController
{
    /**
     * @Route("/produit/json", name="produit_json")
     */
    public function index(): Response
    {
        return $this->render('produit_json/index.html.twig', [
            'controller_name' => 'ProduitJsonController',
        ]);
    }

    /**
     * @Route("/liste",name="liste")
     */
    public function getProduits(ProduitAcheterRepository $repo,NormalizerInterface $ni){
        $produits = $repo->findAll();
        $json = $ni->normalize($produits,'json',['groups' => 'produit']);
        return new Response(json_encode($json));
    }
    /**
     * @Route("/addProduitAcheterJSON", name="addProduitAcheterJSON")
     */
    public function addProduitJSON(Request $request, NormalizerInterface $ni, EntityManagerInterface $entityManager){
        $product = new ProduitAcheter();
        $category = new Category();
        $product->setNom($request->get('nom'));
        $product->setDescription($request->get('description'));
        $product->setQte($request->get('qte'));
        $product->setMarque($request->get('marque'));
        $product->setPrix($request->get('prix'));

        $product->setImagePath("test");
        $product->setCategory($this->getDoctrine()->getRepository(Category::class)->find(1));
        $c=$request->get('category');

        $entityManager->persist($product);
        $entityManager->flush();
        $jsonContent = $ni->normalize($product, 'json',['groups' => 'produit']);
        return new Response(json_encode($jsonContent));
        }
}
