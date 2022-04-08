<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/inscription", name="user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $newFilename = md5(uniqid()).'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setPhoto($newFilename);
            }
            $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));
            $user->setStatus(0);
            $roles = ['ROLE_USER'];
            $user->setRoles($roles);
            $message = (new \Swift_Message( 'INSCRIPTION'))
                ->setFrom('Reloua.tunisie@gmail.com')

                ->setTo($user->getEmail())
                ->setBody(
                    'VOTRE COMPTE A ETE CREE AVEC SUCCEE! CLIQUER SUR CE LIEN POUR VALIDER LE COMPTE: http://127.0.0.1:8000/confirm '
                )
            ;
            $mailer->send($message);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
       return $this->redirectToRoute('app_login');
    }
    /**
     * @Route("/confirm", name="confirmation")
     */
    public function confirm(UserRepository $userRepository, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        if($request->isMethod('POST')){
            $email =  $request->get('email');
            $user =  $userRepository->findOneBy(['email'=>$email]);
            var_dump($user);
            $user->setStatus(1);
            $em->persist($user);
            $em->flush();
            echo $user->getStatus();
        }
        return $this->render('user/confirmation.html.twig');
    }
}
