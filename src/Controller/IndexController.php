<?php

namespace App\Controller;
use App\Entity\Category;
use App\Form\MontageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use App\Entity\ProduitAcheter;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Montage;
use App\Form\MontageFormType;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @route("/montage", name="addmontage")
     */
    function add(Request $request,\Swift_Mailer $mailer){
        $montage=new Montage() ;
        $montage->setEmail($this->getUser()->getEmail());

        $form=$this->createForm(MontageType::class,$montage);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($montage);
            $em->flush();

            $message = (new \Swift_Message( 'Demande Montage!'))
                ->setFrom('Reloua.tunisie@gmail.com')

                ->setTo($montage->getEmail())
                ->setBody(

                    $this->renderView(
                        'montage/email.html.twig', compact('montage')
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);
            $this->addFlash('success', 'Montage ajouté');
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
     * @route("/affichemobile",name="AfficheMobile")
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function Affichemobile(NormalizerInterface $normalizer,MontageRepository $repo): Response
    {
        $repo=$this->getDoctrine()->getRepository(Montage::class) ;
        $montage=$repo->findAll();
        $jsonContent=$normalizer->normalize($montage,'json',['groups'=>'post:read']);


        return new Response(json_encode($jsonContent));

        //return $this->render('montage/affiche.html.twig',['montage'=>json_encode($montage)]);


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
        $this->addFlash('success', 'Montage supprimé');

        return $this->redirectToRoute('affiche');

    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @route("/Deletemobile/{id}",name="deletem")
     */

    public function deletemobile($id,MontageRepository $repository){
        $montage=$repository->find($id);
        $em = $this->getDoctrine()->getManager() ;
        $em -> remove($montage);
        $em -> flush();
        return  new Response("montage supprimé");

    }
    /**
     * @Route ("/update/{id}", name="update")
     */
    public function UpdateMontage(MontageRepository $repo,$id,Request $request)
    {
        $montage=$repo->find($id);
        $form=$this->createForm(MontageType::class,$montage);
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

    /**
     * @Route("/addmontage", name="add_montage")
     * @Method("POST")
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */

    public function ajouterMontageAction(Request $request)
    {
        $montage = new Montage();
        $Processeur = $request->query->get("Processeur");
        $CarteGraphique = $request->query->get("CarteGraphique");
        $CarteMere = $request->query->get("CarteMere");

        $DisqueSysteme = $request->query->get("DisqueSysteme");
        $Boitier = $request->query->get("Boitier");
        $StockageSupp = $request->query->get("StockageSupp");
        $em = $this->getDoctrine()->getManager();

        $montage->setCarteGraphique($CarteGraphique);
        $montage->setProcesseur($Processeur);
        $montage->setCarteMere($CarteMere);
        $montage->setDisqueSysteme($DisqueSysteme);
        $montage->setBoitier($Boitier);
        $montage->setStockageSupp($StockageSupp);

        $em->persist($montage);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($montage);
        return new JsonResponse($formatted);

    }
}
