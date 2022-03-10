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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/indexAdmin.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
    /**
     * @Route("/edit/{id}", name="user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('photo')->getData();
            //some changes
            //sdgdsg

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

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
            $entityManager->flush();
            $this->addFlash('success', 'VOTRE CHAMP EST MODIFIER AVEC SUCCESS');


            return $this->redirectToRoute('admin_index', [], \Symfony\Component\HttpFoundation\Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="user_delete", methods={"POST","GET"})
     */
    public function delete( User $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();


        return $this->redirectToRoute('admin_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/user/trieee", name="trieee")
     */
    public function T()
    {

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
            'SELECT u FROM App\Entity\User u
            ORDER BY u.lastName'
        );


        $rep = $query->getResult();

        return $this->render('user/indexAdmin.html.twig',
            array('users' => $rep));
    }
    /**
     * @Route("/user/usersPDF", name="usersPDF")
     */
    public function UsersPDF()
    {
        $options = new Options();
        $options->set('defaultFont', 'Roboto');


        $dompdf = new Dompdf($options);
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        // return $this->render('Back-office/UsersPDF.html.twig', array("users" => $users));
        $data = array(
            'headline' => 'my headline'
        );
        $html = $this->renderView('/usersPDF.html.twig', array("users" => $users));


        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("testpdf.pdf", [
            "Attachment" => true
        ]);

    }
    /**
     * @Route("/recherche", name="Recherche")
     */

    public function Recherche(UserRepository $repository,Request $request)
    {
        $data=$request->get('search');
        $Reponse=$repository->findBy(['firstName'=>$data]);
        return $this->render('user/indexAdmin.html.twig', [
            'users' => $Reponse,
        ]);

    }

}