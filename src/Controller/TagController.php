<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route('/tags', name: 'app_tags')]
    public function index(TagRepository $tagRepository): Response
    {
        $tags = $tagRepository->findAll();

        return $this->render('tag/index.html.twig', [
            'tags' => $tags,

        ]);
    }

    /**
     * @Route("/tag/{id}/delete", name="app_tag_delete")
     */
    public function delete(Tag $tag, EntityManagerInterface $entityManager): Response
    {

        $tagRef = $entityManager->getReference(Tag::class, $tag->getId());
        $entityManager->remove($tagRef);
        $entityManager->flush();

        return $this->redirectToRoute('app_tags');
    }

    /**
     * @Route("/tag/{id}/edit", name="app_tag_edit")
     * @return Response
     */
    public function edit(Tag $tag, Request $request, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $tagLibelle = $request->request->get('libelle', '');

        $tag->setLibelle($tagLibelle);
        $entityManager->flush();

        return $this->redirectToRoute('app_tags');

    }
}
