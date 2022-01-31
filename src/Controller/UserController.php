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
     * @Route("user/new", name="user")
     */
    public function newUser(EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $user->setFirstname('Francis')
            ->setLastname('Huster')
            ->setEmail('francishuster' . rand(1, 1000) . '@gamil.com');

        $entityManager->persist($user);
        $entityManager->flush();

        return new Response(sprintf('Un nouvel user avec l\'id #%d et le mail %s à été crée',
            $user->getId(),
            $user->getEmail()
        ));
    }
}
