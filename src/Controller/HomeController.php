<?php

namespace App\Controller;

use App\Service\MarkdownHelper;
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