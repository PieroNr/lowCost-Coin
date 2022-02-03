<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;
use App\Repository\QuestionRepository;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;

class HomeController extends AbstractController
{


    /**
     * @Route("/questions/new")
     */
    public function newQuestion(EntityManagerInterface $entityManager){

        $answer = new Answer();
        $answer->setContent('Une super réponse !')
            ->setUsername('Francis')
            ->setCreatedAt(new \DateTime());

        $question = new Question();
        $question->setName('Bonne question ?')
            ->setSlug('boon-question' . rand(0, 1000))
            ->setQuestion('Je pose de super questions');




        if (rand(1, 10) > 2){
            $question->setAskedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
        }

        $answer->setQuestion($question);

        $entityManager->persist($answer);
        $entityManager->persist($question);
        $entityManager->flush();

        return new Response(sprintf('Votre question %s avec l\'id %d est inscrite en BDD',
            $question->getSlug(),
            $question->getId()));

    }

    /**
     * @Route("/questions/{slug}", name="app_show")
     */
    public function show(Question $question){


        $answers = [
            'Je ne suis pas spécialement magicien moi !',
            'As-tu essayé de fermer les fenêtres et de recommencer ?',
            'Crame tout !'
        ];

        return $this->render('questions/show.html.twig', [
            'question' => $question,
            'answers' => $answers
        ]);
    }

    /**
     * @Route("/question/{slug}/vote", name="app_question_vote", methods={"POST"})
     * @param Question $question
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function questionVote(Question $question, Request $request, EntityManagerInterface $entityManager): RedirectResponse
    {
        $vote = $request->request->get('vote');

        if ($vote === "up") {
            $question->upVote();
        } else {
            $question->downVote();
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_show', [
            'slug' => $question->getSlug()
        ]);
    }

    /**
     * @Route("/questions/{id}/delete", name="app_question_delete")
     */
    public function questionDelete($id, EntityManagerInterface $entityManager){
        $question = $entityManager->getReference(Question::class, $id);
        $entityManager->remove($question);
        $entityManager->flush();

        return $this->redirectToRoute('app_homepage');
    }





}