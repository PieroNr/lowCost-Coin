<?php

namespace App\Controller;

use App\Entity\Question;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     * @return Response
     */
    public function homepage(Environment $environment, MarkdownHelper $helper){

        $twig = $environment->render('questions/homepage.html.twig', []);
        dump($this->getParameter('cache_system'));
        return new Response($twig);
    }

    /**
     * @Route("/questions/new")
     */
    public function newQuestion(EntityManagerInterface $entityManager){

        $question = new Question();
        $question->setName('Comment rendre une pizza ?')
            ->setSlug('comment-rendre-une-pizza' . rand(0, 1000))
            ->setQuestion(<<<EOF
'Ma pizza finalement ne convient pas à mon intérieur, 
est-il possible de la retourner au magasin ?
EOF
            );

        if (rand(1, 10) > 2){
            $question->setAskedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
        }

        $entityManager->persist($question);
        $entityManager->flush();

        return new Response(sprintf('Votre question %s avec l\'id %d est inscrite en BDD',
            $question->getSlug(),
            $question->getId()));

    }

    /**
     * @Route("/questions/{ma_wildcard}", name="app_show")
     */
    public function show($ma_wildcard, MarkdownHelper $helper){

        $answers = [
            'Je ne suis pas spécialement magicien',
            'Test1',
            'test2'
        ];

        $question_text="Bitch **bitch** bitch !";

        $parsedQuestion = $helper->parse($question_text);



        return $this->render('questions/show.html.twig', [
            'question' => sprintf('La question : %s', $ma_wildcard),
            'answers' => $answers,
            'question_text' => $question_text
        ]);
    }





}