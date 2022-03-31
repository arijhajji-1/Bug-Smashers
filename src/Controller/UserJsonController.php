<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
* @Route("/json/")
*/
class UserJsonController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("recuperer_users")
     * @return Response
     */
    public function recupererUsers(): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $jsonContent = null;
        $i = 0;
        foreach ($users as $user) {
            $jsonContent[$i]['id'] = $user->getId();
            $jsonContent[$i]['tel'] = $user->getTelephone();
            $jsonContent[$i]['nom'] = $user->getFirstName();
            $jsonContent[$i]['prenom'] = $user->getLastName();
            $jsonContent[$i]['email'] = $user->getEmail();
            $jsonContent[$i]['roles'] = $user->getRoles();
            $jsonContent[$i]['password'] = $user->getPassword();
            $i++;
        }
        $json = json_encode($jsonContent);
        return new Response($json);
    }

    /**
     * @Route("recuperer_user_par_email")
     * @return Response
     */
    public function recupererUser(Request $request): Response
    {
        $email = $request->get("email");

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'email' => $email
        ]);

        $jsonContent[0]['id'] = $user->getId();
        $jsonContent[0]['tel'] = $user->getTelephone();
        $jsonContent[0]['nom'] = $user->getFirstName();
        $jsonContent[0]['prenom'] = $user->getLastName();
        $jsonContent[0]['email'] = $user->getEmail();
        $jsonContent[0]['roles'] = $user->getRoles();
        $jsonContent[0]['password'] = $user->getPassword();

        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("verication_mot_de_passe")
     * @return Response
     */
    public function verifierUser(Request $request): Response
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'email' => $email
        ]);

        if ($user) {
            if ($this->passwordEncoder->isPasswordValid($user, $password)) {
                return new Response(json_encode(['isValid' => true]));
            } else {
                return new Response(json_encode(['isValid' => false]));
            }
        } else {
            return new Response(json_encode(['isValid' => false]));
        }
    }

    /**
     * @Route("inscription_User")
     * @throws Exception
     */
    public function inscriptionCandidat(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();

        // encode the plain password
        $user

            ->setRoles(["ROLE_User"])
            ->setEmail($request->get('email'))

            ->setPassword($passwordEncoder->encodePassword(
                $user,
                $request->get('password')
            ));

        $dateNaissance =
            DateTime::createFromFormat('d/m/Y',
                $request->get('day') . "/" .
                $request->get('month') . "/" .
                $request->get('year')
            );


           $user
            ->setNom($request->get('nom'))
            ->setPrenom($request->get('prenom'))
            ->setTel($request->get('tel'))
            ->setSexe($request->get('sexe'))
            ->setDateNaissance($dateNaissance);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->persist($user);
        $entityManager->flush();

        return new Response("inscription effectué");
    }


}