<?php

namespace App\Controller;
use \Symfony\Bundle\MonologBundle\SwiftMailer;

use App\Form\MontageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Montage;
use App\Form\MontageFormType;
use App\Repository\MontageRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class MontageController extends AbstractController
{
    /**
     * @Route("/montage", name="montage")
     */
    public function index(): Response
    {
        return $this->render('montage/montage.html.twig', [
            'controller_name' => 'MontageController',
        ]);
    }


}
