<?php

namespace App\Controller;

use App\Entity\Evenement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Actualite;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ActualiteType;
use App\Repository\ActualiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FileUploader;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\ActualiteFormType;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Bundle\FrameworkBundle\Controller;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class ActualiteController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(ActualiteRepository $actualiteRepository): Response
    {
        $repo=$this->getDoctrine()->getRepository(Evenement::class);
        $evenement=$repo->findAll();
        return $this->render('actualite/index.html.twig', [
            'actualites' => $actualiteRepository->findAll(),
            'evenements' => $evenement,
        ]);
    }

    /**
     * @Route("/actualite/ajouter", name="actualite_ajout")
     */
    public function ajouter(Request $request, EntityManagerInterface $entityManager
        , FileUploader $fileUploader): Response
    {
        $actualite = new Actualite();
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imageName')->getData();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                $actualite->setImageName($fileName);
            }
            $entityManager->persist($actualite);
            $entityManager->flush();

            return $this->redirectToRoute('actualite_ajout', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('actualite/ajouter.html.twig', [
            'actualite' => $actualite,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/actualite/affiche", name="actualite_affiche")
     */
    public function affiche(ActualiteRepository $repo){

        $repo=$this->getDoctrine()->getRepository(Actualite::class);
        $actualite=$repo->findAll();
        return $this->render('/actualite/affiche.html.twig',[
            'actualites'=>$actualite
        ]);
    }

    /**
     * @Route("/supprimer{id}", name="supprimer")
     */
    public function SupprimerActualité($id, ActualiteRepository $repo)
    {
        $actualite=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($actualite);
        $em->flush();
        return $this->redirectToRoute('actualite_affiche');
    }
    /**
     * @Route ("/modifier{id}", name="modifier")
     */
    public function ModifierActualite(ActualiteRepository $repo,$id,Request $request)
    {
        $actualite=$repo->find($id);
        $form=$this->createForm(ActualiteType::class,$actualite);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('actualite_affiche');
        }
        return $this->render('actualite/modifier.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/actualite/description/{id}",name="actualite_description")
     */
    public function actualiteDesc(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $actualite = $entityManager->getRepository(Actualite::class)->find($id);

        return $this->render("actualite/description.html.twig", [
            "actualite" => $actualite,
        ]);
    }


    /**
     * @Route("/listeActualite",name="listeActualite")
     */
    public function listeActualite(NormalizerInterface $normalizer)
    {
        $repo= $this->getDoctrine()->getRepository(Actualite::class);
        $actualite = $repo->findAll();
        $jsonContent = $normalizer->normalize($actualite, 'json',['groups'=>'post:read']);


        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("/ajoutActualite",name="ajoutActualiteJSON")
     */
    public function ajoutActualiteJSON(Request $request,NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $actualite = new Actualite();
        $actualite->setTitre($request->get('titre'));

        $actualite->setDate(new \DateTime());
        $actualite->setDescription($request->get('description'));
        $actualite->setimageName($request->get('imageName'));
        $em->persist($actualite);
        $em->flush();
        $jsonContent = $normalizer->normalize($actualite, 'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/updateAcualite",name="updateActualiteJSON")
     * @Method ("PUT")
     */
    public function updateAcualiteJSON(Request $request){
        $em = $this->getDoctrine()->getManager();
        $actualite = $this->getDoctrine()->getManager()->getRepository(Actualite::class)->find($request->get("id"));
        $actualite->setTitre($request->get('titre'));
        $actualite->setDate(new \DateTime());
        $actualite->setDescription($request->get('description'));
        $em->persist($actualite);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        return new Response("actualite updated successfully");

    }


    /**
     * @Route("/deleteActualite",name="deleteActualiteJSON")
     * @Method ("DELETE")
     */
    public function deleteActualite(Request $request,  EntityManagerInterface $em)
    {
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $Actualite  = $em->getRepository(Actualite::class)->find($id);
        if($Actualite != null)
        {
            $em->remove($Actualite);
            $em->flush();

            return new JsonResponse("Actualite Deleted ");
        }
        return new JsonResponse("not found");
    }
}
