<?php

namespace App\Controller;
use App\Entity\AvisReparation;
use App\Entity\Montage;
use App\Entity\User;

use App\Entity\Reparation;
use App\Form\EtatType;
use App\Message\GenerateReport;
use App\Repository\AvisReparationRepository;
use App\Repository\MontageRepository;
use App\Repository\ReparationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexbackController extends AbstractController
{
    /**
     * @Route("/indexback", name="indexback")
     */
    public function index(): Response
    {
        return $this->render('indexback/index.html.twig', [
            'controller_name' => 'IndexbackController',
        ]);
    }
}
