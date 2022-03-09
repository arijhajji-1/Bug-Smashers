<?php

namespace App\Controller;

use App\Entity\ProduitAcheter;
use App\Repository\CommandeRepository;
use App\Repository\ProduitAcheterRepository;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/cart", name="cart_")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SessionInterface $session, produitAcheterRepository $produitAcheterRepository)
    {
        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;


        foreach ($panier as $id => $quantite) {
            $TypeP=$produitAcheterRepository->findAll();
            $product = $produitAcheterRepository->find($id);
            $dataPanier[] = [
                "produitAcheter" => $product,
                "quantite" => $quantite,
                "Type"=>$TypeP,
            ];
            $total += $product->getPrix() * $quantite;
        }

        return $this->render('cart/index.html.twig', compact("dataPanier", "total"));
    }




    /**
     * @Route("/add/{id}", name="add")
     */
    public function add(produitAcheterRepository $produitAcheterRepository, SessionInterface $session,$id)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);


        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }
    /**
     * @Route("/add1/{id}", name="add1")
     */
    public function add1(produitAcheterRepository $produitAcheterRepository, SessionInterface $session,$id)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);


        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("produit_index");
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove(produitAcheterRepository $produitAcheterRepository, SessionInterface $session,$id)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);


        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(produitAcheterRepository $produitAcheterRepository, SessionInterface $session,$id)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);


        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }


    /**
     * @Route("/delete", name="delete_all")
     */
    public function deleteAll(SessionInterface $session)
    {
        $session->remove("panier");

        return $this->redirectToRoute("info_cart");
    }



}
