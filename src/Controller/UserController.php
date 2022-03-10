<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\CommentaireRepository;
use App\Repository\PublicationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{



    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, PublicationRepository $publicationRepository, CommentaireRepository $commentaireRepository): \Symfony\Component\HttpFoundation\Response
    {
        $publi = $publicationRepository->findAll();
        $comments = array();
        foreach ($publi as $p){
            array_push($comments,count($p->getCommentaires()));
        }
        $filtres = ['blue', 'faklfzlkfzklklfza', 'yellow', 'red'];
        $commentaires = $commentaireRepository->findAll();
        foreach($commentaires as $comm){
            $newComm = str_replace($filtres, "*****", $comm->getText());
            $comm->setText($newComm);
        }
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'publications' => $publi,
            'commentaires' => $commentaires,
            'nbrecomm' => $comments
        ]);
    }

}
