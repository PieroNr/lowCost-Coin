<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AnswerController extends AbstractController
{
    #[Route('/question/{slug}/answer', name: 'app_answer', methods: ['POST'])]
    public function index(Question $question, Request $request, EntityManagerInterface $entityManager, UserInterface $user): RedirectResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if($this->getUser()==$question->getProductId()->getSellerId()){
            $answer = new Answer();
            $answerText = $request->request->get('answer');

            $answer->setContent($answerText);
            $answer->setSellerId($user);
            $answer->setUsername($user->getFirstname());
            $answer->setQuestion($question);

            $question->addAnswer($answer);

            $entityManager->persist($answer);
            $entityManager->flush();
        }


        return $this->redirectToRoute('app_product_show', [
            'slug' => $question->getProductId()->getSlug()
        ]);
    }
}
