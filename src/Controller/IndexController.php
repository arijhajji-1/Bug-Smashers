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
     * @Route("/", name="index")
     */
    public function HomePage(): Response
    {
        return $this->render('/animation.html.twig');
    }
    /**
     * @Route("/index", name="home")
     */
    public function Home(): Response
    {
        return $this->render('index/homePage.html.twig');
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

}
