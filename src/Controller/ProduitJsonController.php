<?php

namespace App\Controller;

use App\Entity\Avis_Produit;
use App\Entity\Category;
use App\Entity\ProduitAcheter;
use App\Entity\ProduitLouer;
use App\Form\ProduitAcheterType;
use App\Repository\Avis_ProduitRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProduitAcheterRepository;
use App\Repository\ProduitLouerRepository;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @Route("/getAvisProduit/{id}", name="produitAvis_json")
     */
    public function avisProd($id, NormalizerInterface $ni): Response
    {

        try{$avis =$this->getDoctrine()->getRepository(ProduitAcheter::class)->find($id)->getAvis();}
        catch (\Exception $e){
            $avis=null;
        }
        $json = $ni->normalize($avis,'json',['groups' => 'avis']);
        return new Response(json_encode($json));
    }
    /**
     * @Route("/addAvisProduit/{id}", name="addAvis_json")
     */
    public function addAvisProd(Request $request, $id, NormalizerInterface $ni): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(ProduitAcheter::class)->find($id);
        $avis = new Avis_Produit();
        $avis->setProduitAcheter($produit);
        $avis->setDescription($request->get('description'));
        $avis->setRating($request->get('rating'));
        $avis->setEmail($request->get('email'));
        $avis->setNom($request->get('nom'));
        $json = $ni->normalize($avis,'json',['groups' => 'avis']);
        $entityManager->persist($avis);
        $entityManager->flush();
        return new Response(json_encode($json));
    }

    /**
     * @Route("/listepa",name="listePa")
     */
    public function getProduits(ProduitAcheterRepository $repo,NormalizerInterface $ni){
        $produits = $repo->findAll();
        $json = $ni->normalize($produits,'json',['groups' => 'produit']);
        return new Response(json_encode($json));
    }
    /**
     * @Route("/listepl",name="listePl")
     */
    public function getProduitsL(ProduitLouerRepository $repo,NormalizerInterface $ni){
        $produits = $repo->findAll();
        $json = $ni->normalize($produits,'json',['groups' => 'produit']);
        return new Response(json_encode($json));
    }
    /**
     * @route("/recherchemobileprodA",name="recherchemobileprodA")
     */
    public function recherchemobileprodA(NormalizerInterface $normalizer,ProduitAcheterRepository $repo , request $request): Response
    { $repo=$this->getDoctrine()->getRepository(ProduitAcheter::class) ;
        $data=$request->get('searchbar');
        //  $reparation=$repo->findBy(['category'=>$data]);
        $produit = $this->getDoctrine()->getRepository(ProduitAcheter::class)->findByExampleField($data);
        $datas=array();
        foreach($produit as $key=>$blog) {
            $datas[$key]['id']=$blog->getId();
            $datas[$key]['nom']=$blog->getNom();
            $datas[$key]['description']=$blog->getDescription();
            $datas[$key]['qte']=$blog->getQte();
            $datas[$key]['marque']=$blog->getMarque();
            $datas[$key]['prix']=$blog->getPrix();
            $datas[$key]['imagePath']=$blog->getImagePath();
            $datas[$key]['category']=$blog->getCategory()->getId();
        }


        return new JsonResponse($datas);

        dump($jsonContent);
        die;

    }
    /**
     * @Route("/displayEnn", name="displayEnn")
     * Method ({"GET","POST"})
     */
    public function allss(NormalizerInterface $normalizer, CategoryRepository $repoS): Response
    {

        $em=$this->getDoctrine()->getManager();
        $blogs=$em->getRepository(ProduitAcheter::class)->findAll();

        $datas=array();
        foreach($blogs as $key=>$blog) {
            $datas[$key]['id']=$blog->getId();
            $datas[$key]['nom']=$blog->getNom();
            $datas[$key]['description']=$blog->getDescription();
            $datas[$key]['qte']=$blog->getQte();
            $datas[$key]['marque']=$blog->getMarque();
            $datas[$key]['prix']=$blog->getPrix();
            $datas[$key]['imagePath']=$blog->getImagePath();
            $datas[$key]['category']=$blog->getCategory()->getId();
        }
        return new JsonResponse($datas);

    }
    /**
     * @Route("/catprodAN", name="catprodAN")
     * Method ({"GET","POST"})
     */
    public function catprodAN(NormalizerInterface $normalizer, CategoryRepository $repoS): Response
    {

        $em=$this->getDoctrine()->getManager();
        $blogs=$em->getRepository(Category::class)->findAll();

        $datas=array();
        foreach($blogs as $key=>$blog) {
            $datas[$key]['label']=$blog->getLabel();
            $datas[$key]['prod']=$blog->getProduitAcheter()->count();
        }
        return new JsonResponse($datas);

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

        $product->setImagePath($request->get('imagePath'));
        $c=$request->get('category');
        $product->setCategory($this->getDoctrine()->getRepository(Category::class)->find($c));

        $entityManager->persist($product);
        $entityManager->flush();
        $jsonContent = $ni->normalize($product, 'json',['groups' => 'produit']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/getproduitAJSON", name = "getProduitacheterJSON")
     */
    public function getProduitAJSON(Request $request, NormalizerInterface $ni){
        $product = $this->getDoctrine()->getRepository(ProduitAcheter::class)->find($request->get('id'));
        $jsonContent = $ni->normalize($product, 'json',['groups' => 'produit']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/addProduitLouerJSON", name="addProduitlouerJSON")
     */
    public function addProduitLJSON(Request $request, NormalizerInterface $ni, EntityManagerInterface $entityManager){
        $product = new ProduitLouer();
        $category = new Category();
        $product->setNom($request->get('nom'));
        $product->setDescription($request->get('description'));
        $product->setEtat($request->get('etat'));
        $product->setDispo($request->get('dispo')=== 'true');
        $product->setMarque($request->get('marque'));
        $product->setPrix($request->get('prix'));
        $product->setImagePath($request->get('imagePath'));
        $c=$request->get('category');
        $product->setCategory($this->getDoctrine()->getRepository(Category::class)->find($c));
        $entityManager->persist($product);
        $entityManager->flush();
        $jsonContent = $ni->normalize($product, 'json',['groups' => 'produit']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/modifProduitAcheterJSON/{id}", name="modifProduitAcheterJSON")
     */
    public function modifyProduitJSON(Request $request, $id, NormalizerInterface $ni,
                                      EntityManagerInterface $entityManager){
        $product = $this->getDoctrine()->getRepository(ProduitAcheter::class)->find($id);
        $category = new Category();
        $product->setNom($request->get('nom'));
        $product->setDescription($request->get('description'));
        $product->setQte($request->get('qte'));
        $product->setMarque($request->get('marque'));
        $product->setPrix($request->get('prix'));
        $product->setImagePath("azerty");
        $c=$request->get('category');
        $product->setCategory($this->getDoctrine()->getRepository(Category::class)->find($c));
        $entityManager->flush();
        $jsonContent = $ni->normalize($product, 'json',['groups' => 'produit']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/deleteProduitAcheterJSON/{id}", name="deleteProduitAcheterJSON")
     */
    public function deleteProduitJSON(Request $request, $id, NormalizerInterface $ni,
                                      EntityManagerInterface $entityManager){
        $product = $this->getDoctrine()->getRepository(ProduitAcheter::class)->find($id);
        $entityManager->remove($product);
        $entityManager->flush();
        $jsonContent = $ni->normalize($product, 'json',['groups' => 'produit']);
        return new Response(json_encode($jsonContent));
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
     * @Route("/deleteC/{id}",name="deleteC")
     */
    public function deleteC($id, NormalizerInterface $ni,
                            EntityManagerInterface $entityManager){
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $entityManager->remove($category);
        $entityManager->flush();
        $jsonContent = $ni->normalize($category, 'json',['groups' => 'category']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/addCatJSON", name="addCatJSON")
     */
    public function addCategoryJSON(Request $request, NormalizerInterface $ni, EntityManagerInterface $entityManager){
        $category = new Category();
        $category->setLabel($request->get('label'));
        $entityManager->persist($category);
        $entityManager->flush();
        $jsonContent = $ni->normalize($category, 'json',['groups' => 'category']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/ModifyCatJSON{id}", name="ModifyCatJSON")
     */
    public function ModifyCatJSON(Request $request, $id, NormalizerInterface $ni,
                                  EntityManagerInterface $entityManager){
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $category->setLabel($request->get('label'));
        $entityManager->flush();
        $jsonContent = $ni->normalize($category, 'json',['groups' => 'category']);
        return new Response(json_encode($jsonContent));
    }
}
