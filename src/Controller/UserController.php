<?php

namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("user/{id}", name="app_profil")
     */
    public function showProfil(User $user): Response
    {
        return $this->render('profil/profil.html.twig', [
            'user' => $user
        ]);
    }
}
