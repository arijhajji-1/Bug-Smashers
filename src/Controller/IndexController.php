<?php

namespace App\Controller;
use App\Entity\Category;

use App\Form\MontageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Montage;
use App\Form\MontageFormType;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\MontageRepository;
class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    /**
     * @Route("/about", name="about")
     */
    public function about(): Response
    {
        return $this->render('index/about.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    /**
     * @Route("/produit", name="produit")
     */
    public function produit(): Response
    {
        return $this->render('index/produit.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    /**
     * @Route("/forum", name="forum")
     */
    public function forum(): Response
    {
        return $this->render('index/forum.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('index/contact.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    /**
     * @Route("/inscri", name="inscri")
     */
    public function inscri(): Response
    {
        return $this->render('index/inscri.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @Route("/montage", name="montage")
     */
    public function montage(): Response
    {
        return $this->render('index/montage.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @route("/montage")
     */
    function add(Request $request){
        $montage=new montage() ;
        $form=$this->createForm(MontageType::class,$montage);
        $form->add('Ajouter',SubmitType::class) ;
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($montage);
            $em->flush();
            return $this->redirectToRoute("montage");


        }

        return $this->render('montage/montage.html.twig',['form'=>$form->createView()]);


    }

        /**
* @Route("/affiche", name="affiche")
*/
    public function AfficheMontage(MontageRepository $repo)
    {
        $repo=$this->getDoctrine()->getRepository(Montage::class);
        $montage=$repo->findAll();
        return $this->render('montage/affiche.html.twig',[
            'montage'=>$montage
        ]);
    }

/**
* @Route("/delete{id}", name="delete")
*/
    public function DeleteMontage($id, MontageRepository $repo)
    {
        $montage=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($montage);
        $em->flush();
        return $this->redirectToRoute('affiche');
    }
    /**
     * @Route ("/update/{id}", name="update")
     */
    public function UpdateMontage(MontageRepository $repo,$id,Request $request)
    {
        $montage=$repo->find($id);
        $form=$this->createForm(MontageType::class,$montage);
        $form->add('update',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('affiche');
        }
        return $this->render('montage/update.html.twig',[
            'form'=>$form->createView()
        ]);
    }


}
