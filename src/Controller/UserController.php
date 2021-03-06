<?php

namespace App\Controller;


use App\Entity\Note;
use App\Entity\Product;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{

    /**
     * @Route("/users", name="app_users")
     */
    public function index(UserRepository $userRepository): Response
    {

        /*$products = $repository->findAllProductOrderByDate();*/
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,

        ]);
    }

    /**
     * @Route("user/{id}", name="app_profil")
     */
    public function showProfil(User $user, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, Request $request): Response
    {

        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_profil',[
                'id' => $user->getId()
            ]);
        }


        return $this->render('user/profil.html.twig', [
            'user' => $user,
            'registrationForm' => $form->createView(),

        ]);
    }

    /**
     * @Route("/user/{id}/vote", name="app_user_vote", methods={"POST"})
     */
    public function userVote(User $user, Request $request, EntityManagerInterface $entityManager, UserInterface $userInterface, NoteRepository $noteRepository): RedirectResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($userInterface){
            $findNote = $noteRepository->findBy(['userSender' => $userInterface->getId(), 'userReceiver' => $user->getId()]);

            $vote = $request->request->get('vote');
            if (!empty($findNote)){
                $note = $findNote[0];
                if ($vote === "up") {
                    $note->setNote(1);
                } elseif ($vote === "down") {
                    $note->setNote(0);;
                }

            } else {
                $note = new Note();
                if ($vote === "up") {
                    $note = $userInterface->sendUpNote($user);
                } elseif ($vote === "down") {
                    $note = $userInterface->sendDownNote($user);;
                }
                $entityManager->persist($note);
            }

            $entityManager->flush();
        } else {
            return $this->redirectToRoute('app_login');
        }


        return $this->redirectToRoute('app_profil', [
            'id' => $user->getId()
        ]);
    }

    /**
     * @Route("/user/{id}/delete", name="app_user_delete")
     */
    public function delete(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {

        $userRef = $entityManager->getReference(User::class, $user->getId());
        $entityManager->remove($userRef);
        $entityManager->flush();

        return $this->redirectToRoute('app_homepage');
    }
}
