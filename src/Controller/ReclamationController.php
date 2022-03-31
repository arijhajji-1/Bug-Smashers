<?php

namespace App\Controller;
use App\Form\SearchReclamationType;
use CalendarBundle\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\ReclamationType;
//use http\Env\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reclamation;
use App\Repository\ReclamationRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Gedmo\Mapping\Annotation as Gedmo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation/home", name="acceuil_reclamation")
     */

    public function home(){

        return $this->render('reclamation/home.html.twig',
            ['controller_name'=>'ReclamationController']);

    }






    /**
     * @Route("/reclamation/add", name="ajout_reclamation")
     */
//fonction de l'utilisateur pour ajouter une réclamation
    public function addrecl(Request $request, FlashyNotifier $flashy){

        //créer une nouvelle réclamation
        $reclaim= new Reclamation();
        // Cette date sera donc préaffichée dans le formulaire, cela facilite le travail de l'utilisateur
        $reclaim->setDate(new \Datetime());
        //recuperer le formulaire
        $formBuilder = $this->createFormBuilder($reclaim);
        $formBuilder
            ->add('idCommande',IntegerType::class)
            ->add('categorie',ChoiceType::class,
                ['choices'  => [
                    'Location' => "Location",
                    'Montage' => "Montage",
                    'Reparation' => "Reparation",],
                ])
            ->add('sujet',TextType::class)
            ->add('description',TextareaType::class)
            ->add('date',DateType::class);
        //$form= $this->createForm(ReclamationType::class,$reclaim);
        //ajout d'un bouton submit
        //$form->add('ajouter', SubmitType::class);


        $form= $formBuilder->getForm();
        /*$request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
        }*/
        $form->handleRequest($request);
        //controle de saisie
        if($form->isSubmitted()&& $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($reclaim);
            $em->flush();
            // $flashy->success('relamation enregistrée !');
            $this->addFlash('success', 'relamation enregistrée !');
            return $this->redirectToRoute('ajout_reclamation');
        }
        //rendre la vue et generer le html du formulaire
        return $this->render('reclamation/reclamation.html.twig',[
            'form'=>$form->createView()]);

    }






    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/reclamation/list", name="list_reclamation")
     */

    //fonction de l'admnistrateur pour afficher la liste des réclamations
    public function reclaimlist( Request $request, PaginatorInterface $paginator, ReclamationRepository $repo)
    {
//NormalizableInterface $normalizer,

        $donnees = $this->getDoctrine()->getRepository(Reclamation::class)->findBy([], ['date' => 'desc']);
        $reclamations = $paginator->paginate($donnees,//requête contenant les données à paginer
            $request->query->getInt('page', 1),
            6);


        // $jsoncontent= $normalizer->normalize($reclamations,'json',['groups'=>'post:read']);
        return $this->render('reclamation/listeReclamation.html.twig', [
            //'data'=>$jsoncontent
            'reclamations' => $reclamations,
        ]);


    }







    /**
     * @Route ("/update/{id}", name="update")
     */
    //fonction de l'utilisateur pour modifier sa réclamation
    public function UpdateReclamation(ReclamationRepository $repo,$id,Request $request)
    {
        $reclamation = $repo->find($id);
        // Et on construit le formBuilder avec cette instance de reclamation
        $formBuilder = $this->createFormBuilder($reclamation);
        $formBuilder
            ->add('idCommande',IntegerType::class)
            ->add('categorie',ChoiceType::class,
                ['choices'  => [
                    'Location' => "Location",
                    'Montage' => "Montage",
                    'Reparation' => "Reparation",],
                ])
            ->add('sujet',TextType::class)
            ->add('description',TextareaType::class)
            ->add('date',DateType::class);
        // $form = $this->createForm(ReclamationType::class, $reclamation);
        // $form->add('update', SubmitType::class);
        $form= $formBuilder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'relamation modifiée !');
            return $this->redirectToRoute('list_reclamation');
        }
        return $this->render('reclamation/update.html.twig', [
            'form' => $form->createView()
        ]);
    }






    /**
     * @Route("/reclamation/delete/{id}", name="delete_reclamation")
     */

    //fonction de l'administrateur pour supprimer une réclamation
    public function delete ( $id,ReclamationRepository $repository)
    {
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        /* dump($reclamation);
         die();*/
        $em=$this->getDoctrine()->getManager();

        $em->remove($reclamation);
        $em->flush();
        $this->addFlash('success', 'relamation modifiée!');
        return $this->redirectToRoute('list_reclamation');

    }

    /**
     * @return Response
     * @route ("/search/reclamation", name="recherche_reclamation")
     */

    public function recherchebycategorie(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $reclamation=$em->getRepository(Reclamation::class)->findAll();
        if($request->isMethod("POST"))
        {
            $categorie=$request->get('categorie');
            $reclamation=$em->getRepository(Reclamation::class)->findBy(['categorie'=>$categorie]);
        }
        return $this->render('reclamation/searchReclamation.html.twig', [
            'reclamation' => $reclamation,
        ]);

    }


    /**
     * @route("/stat/reclamation", name="stat_reclamation")
     *
     */

    public function statReclamation(ReclamationRepository $repo)
    {
//chercher toutes les reclamations
        $recls= $repo->findAll();
        $categNom=[];
        $categCount=[];
        foreach ($recls as $recl)
        {
            $categNom[]=$recl->getCategorie();
            $categCount[]=is_countable($recl->getId());

        }
        $reclamations=$repo->countday();
        $dates=[];
        $reclcount=[];
        foreach ($reclamations as $reclamation)
        {
            $dates[]= $reclamation['date'];
            $reclcount[]=$reclamation['count'];

        }

        return $this->render('reclamation/statistiqueReclamation.html.twig',
            ['categNom'=>json_encode($categNom),
                'categCount'=>json_encode($categCount),
                'dates'=>json_encode($dates),
                'reclcount'=>json_encode($reclcount),
            ]);

    }


//*********************************supprimer avec json****************************//

    /**
     * @Route("/suppReclamation", name="supp_reclamation")
     * @Method("DELETE")
     */

    public function deleteReclamationAction(Request $request)
    {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(Reclamation::class)->find($id);
        if ($reclamation != null) {
            $em->remove($reclamation);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Reclamation a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id reclamation invalide.");

    }



    //*******************modifier avec json*****************//
    /**
     * @Route("/modReclamation", name="mod_reclamation")
     * @Method("PUT")
     */
    public function modifierReclamationAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(Reclamation::class)
            ->find(
                $request->get("id")
            );
        // $reclamation->setIdCommande($request->get("idCommande"));
        $reclamation->setCategorie($request->get("categorie"));
        $reclamation->setSujet($request->get("sujet"));
        $reclamation->setDescription($request->get("description"));
        $reclamation->setDate(new \DateTime());

        $em->persist($reclamation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reclamation);
        return new JsonResponse("Reclamation a ete modifiee avec success.");

    }


    //*************************ajouter avec json*****************************//


    /**
     * @Route("/ajoutReclamation", name="ajout_reclamation")
     * @Method("POST")
     */
    public function ajouterReclamationAction(Request $request) {


        $em = $this->getDoctrine()->getManager();
        $reclamation= new Reclamation();
        /*$reclamation = $em
            ->getRepository(Reclamation::class)
            ->find($request->get("id"));*/


        $reclamation->setIdCommande("0");
        $reclamation->setCategorie($request->get("categorie"));
        $reclamation->setSujet($request->get("sujet"));
        $reclamation->setDescription($request->get("description"));
        $reclamation->setDate(new \DateTime());
        $em->persist($reclamation);
        $em->flush();
        /*$jsoncontent= $normalizer->normalize($reclamation,'json',['group'=>'post:read']);
        return new Response(json_encode($jsoncontent));*/
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reclamation);
        return new JsonResponse("Reclamation a ete ajoutee avec success.");

    }

//***************afficher les reclamations avec json*************//

    /*
     * @Route("/affReclamation", name="afficher_reclamation")


public function allrecl(){

        $reclamation=$this->getDoctrine()->getManager()->getRepository(Reclamation::class)->findAll();
        $serializer= new Serializer([new ObjectNormalizer()]);
        $formatted= $serializer->normalize($reclamation);

        return new JsonResponse($formatted);

}*/
    /**
     * @Route("/affReclamation", name="afficher_reclamation")
     */
    public function allrecl(NormalizerInterface $normalizer,ReclamationRepository $repo): Response
    {
        $repo=$this->getDoctrine()->getRepository(Reclamation::class) ;
        $reclamation=$repo->findAll();
        $jsonContent=$normalizer->normalize($reclamation,'json',['groups'=>'post:read']);


        return new Response(json_encode($jsonContent));

        dump($jsonContent);
        die;

    }

//**************detail reclamation**********************//

    /**
     * @Route("/detReclamation", name="detail_reclamation")
     * @Method("GET")
     */

    public function detailReclamation(Request $request)
    {
        $id=$request->get("id");
        $em=$this->getDoctrine()->getManager();
        $reclamation=$em->getRepository(Reclamation::class)->find($id);
        $encoder= new JsonEncoder();
        $normalizer=new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object){
            return $object->getDescription;
        });
        $serializer= new Serializer([new ObjectNormalizer()]);
        $formatted= $serializer->normalize($reclamation);

        return new JsonResponse($formatted);


    }












}
