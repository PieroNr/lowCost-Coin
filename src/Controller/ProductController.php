<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\CreateProductFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductController extends AbstractController
{

    /**
     * @Route("/product/new", name="app_product_create")
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $entityManager, UserInterface $user): Response
    {


        $product = new Product();
        $form = $this->createForm(CreateProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setSellerId($user);
            foreach ($form->get('images')->getData() as $image){



            }

            $entityManager->persist($product);
            foreach ($form->get('images')->getData() as $image){

                $image->setProductId($product);

            }
            $entityManager->flush();


            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('product/create.html.twig', [
            'createProductForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/{slug}", name="app_product_show", methods={"GET"})
     * @return Response
     */
    public function show(Product $product): Response
    {

        dd($product);
        if (!$product instanceof Product) {
            throw new NotFoundHttpException('Product not found');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }



}
