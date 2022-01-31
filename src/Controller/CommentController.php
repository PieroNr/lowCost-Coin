<?php

namespace App\Controller;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CommentController extends AbstractController
{
    /**
     * @Route("comments/{id}/vote/{direction<up|down>}", name="app_comment_vote", methods="POST")
     */

    public function commentVote($id, $direction, LoggerInterface $logger){

        $logger->info('Coucou !');
        if($direction === 'up'){
            $voteCount = rand(7, 50);
        }
        else {
            $voteCount = rand(0,5);
        }

        return new JsonResponse([
            'votes' => $voteCount
        ]);
    }



}