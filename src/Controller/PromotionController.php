<?php

namespace App\Controller;

use App\Entity\ProduitAcheter;
use App\Entity\Promotion;
use App\Form\AjouterProduitPromType;
use App\Form\PromotionType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PromotionController extends AbstractController
{
    /**
     * @Route("/produit/promotion", name="promotion")
     */
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        {
            $dql   = "SELECT p FROM App\Entity\Promotion p";
            $query = $em->createQuery($dql);
            $promotion = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                6 // Nombre de résultats par page
            );
            return $this->render('promotion/index.html.twig', [
                'promotions' => $promotion,
            ]);
        }
    }

    /**
     * @Route("/produit/ajouterP", name="produit_promotion_ajout")
     */
    public function ajouterA(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($promotion);
            $entityManager->flush();

            $this->addFlash('ajoutP', 'Promotion ajouté avec succés');

            return $this->redirectToRoute('produit_promotion_ajout', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('promotion/ajouterP.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/single-promo/{id}",name="liste-produit-promo")
     */
    public function singleProductA(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $promotion = $entityManager->getRepository(Promotion::class)->find($id);
        return $this->render("promotion/single-promotion.html.twig", [
            "product" => $promotion->getProduitAcheter(),
        ]);
    }
    /**
     * @Route("/delete-promo/{id}",name="delete-produit-promo")
     */
    public function supprimerProduit($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $promotion = $entityManager->getRepository(Promotion::class)->find($id);
        $entityManager->remove($promotion);
        $entityManager->flush();
        return $this->redirectToRoute("promotion");
    }

    /**
     * @Route("/produit/ajouterAtoP/{id}", name="Ajouter_ProduitToProm")
     */
    public function ajouterAtoP(Request $request, EntityManagerInterface $entityManager, $id,\Swift_Mailer $mailer): Response
    {

        $form = $this->createForm(AjouterProduitPromType::class);
        $produit = $entityManager->getRepository(ProduitAcheter::class)->find($id);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $promotion=$form->get("Promotion")->getData();
            $produit->setPromotion($promotion);
            $entityManager->flush();
            foreach($produit->getWishlists() as $item) {
                $email = $item->getUsers()->getEmail();
                $message = (new \Swift_Message( 'Promotion!!'))
                    ->setFrom('Reloua.tunisie@gmail.com')
                    ->setTo($email)
                    ->setBody(
                        $this->renderView(
                            'wishlist/email.html.twig', compact('produit')
                        ),
                        'text/html'
                    )
                ;
                $mailer->send($message);
            }
            $this->addFlash('ajoutP', 'Promotion ajouté avec succés');


            return $this->redirectToRoute('Ajouter_ProduitToProm', ["id"=>$id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('promotion/Ajouter_ProduitToProm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/delete-Produitpromo/{id}",name="delete-produitpromo")
     */
    public function supprimerProduitPromo($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(ProduitAcheter::class)->find($id);
        $produit->setPromotion(null);
        $entityManager->flush();
        return $this->redirectToRoute("produit_acheter_affichage_back");
    }




}
