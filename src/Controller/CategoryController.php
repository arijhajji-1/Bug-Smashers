<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/affback", name="category")
     */
    public function back(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'Categories' => $categoryRepository->findAll(),
        ]);
    }
}
