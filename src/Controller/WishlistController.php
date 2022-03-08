<?php

namespace App\Controller;

use App\Entity\ProduitAcheter;
use App\Entity\Wishlist;
use App\Form\ProduitAcheterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishlistController extends AbstractController
{
    /**
     * @Route("/AjouterpAWish/{id}", name="AjouterpAWish")
     */
    public function ajouterpAWish(Request $request, int $id,\Swift_Mailer $mailer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(ProduitAcheter::class)->find($id);
        if(!($this->getUser()->getWishlist())){
        $wishlist = new Wishlist();
        $this->getUser()->setWishlist($wishlist);
        $wishlist->addProduitAcheter($produit) ;
        $entityManager->persist($wishlist);}
        else{
            $this->getUser()->getWishlist()->addProduitAcheter($produit) ;
        }
        $entityManager->flush();
        $message = (new \Swift_Message( 'WishList!'))
            ->setFrom('Reloua.tunisie@gmail.com')

            ->setTo($this->getUser()->getEmail())
            ->setBody(

                $this->renderView(
                    'wishlist/email.html.twig', compact('produit')
                ),
                'text/html'
            )
        ;
        $mailer->send($message);
        return $this->redirectToRoute("afficher_wishlist");
    }
    /**
     * @Route("/Afficher_wishlist/", name="afficher_wishlist")
     */
    public function afficher_wishlist(){

        return $this->render('wishlist/index.html.twig', [
            'produitsAcheter' => $this->getUser()->getWishlist()->getProduitAcheter()
        ]);
    }
    /**
     * @Route("/supprimer_wishlist/{id}", name="supprimer_produit_wishlist")
     */
    public function deleteProductA(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produitAcheter = $entityManager->getRepository(ProduitAcheter::class)->find($id);
        $this->getUser()->getWishlist()->removeProduitAcheter($produitAcheter);
        $entityManager->flush();

        return $this->redirectToRoute("afficher_wishlist");
    }

}
