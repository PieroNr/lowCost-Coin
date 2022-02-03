<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Note;
use App\Entity\Product;
use App\Entity\Question;
use App\Form\CreateProductFormType;
use App\Form\QuestionFormType;
use App\Form\SearchForm;
use App\Repository\ImageRepository;
use App\Repository\NoteRepository;
use App\Repository\ProductRepository;
use App\Repository\QuestionRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductController extends AbstractController
{

    /**
     * @Route("/", name="app_homepage")
     * @return Response
     */
    public function homepage(ProductRepository $repository, ImageRepository $imageRepository, Request $request){

        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);

        /*$products = $repository->findAllProductOrderByDate();*/
        $products = $repository->findSearch($data);
        foreach ($products as $product){
            $images = $imageRepository->findAllByProductId($product);
            foreach ($images as $image) {
                $product->addImage($image);

            }
        }


        return $this->render('home/homepage.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

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

            $entityManager->persist($product);
            foreach ($form->get('images')->getData() as $image){
                $image->setProductId($product);
                $product->addImage($image);

            }



            $entityManager->flush();

            $this->AddFlash(
                'succes',
                "Le produit a bien été enregistré  !"
            );


            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('product/create.html.twig', [
            'createProductForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/{slug}", name="app_product_show")
     * @return Response
     */
    public function show(Product $product, ImageRepository $imageRepository, TagRepository $tagRepository, Request $request, UserInterface $user, EntityManagerInterface $entityManager, QuestionRepository $questionRepository): Response
    {

        $seller = $product->getSellerId();

        $images = $imageRepository->findAllByProductId($product);
        $tags = $tagRepository->findAllByProductId($product);
        $questions = $questionRepository->findBy(
            ['productId' => $product],
            ['askedAt' => 'DESC']
        );


        $question = new Question();
        $form = $this->createForm(QuestionFormType::class, $question);
        $form->handleRequest($request);





        if ($form->isSubmitted() && $form->isValid() && $user!=$seller) {

            $question->setProductId($product);
            $question->setBuyerId($user);

            $entityManager->persist($question);
            $entityManager->flush();

        }


        return $this->render('product/show.html.twig', [
            'product' => $product,
            'images' => $images,
            'tags' => $tags,
            'questions' => $questions,
            'noteTotale' => $seller->getTotalNote(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/product/{slug}/vote", name="app_product_vote", methods={"POST"})
     */
    public function questionVote(Product $product, Request $request, EntityManagerInterface $entityManager, UserInterface $user, NoteRepository $noteRepository): RedirectResponse
    {




        if ($user){
            $findNote = $noteRepository->findBy(['userSender' => $user, 'userReceiver' => $product->getSellerId()]);

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
                    $note = $user->sendUpNote($product->getSellerId());
                } elseif ($vote === "down") {
                    $note = $user->sendDownNote($product->getSellerId());;
                }
                $entityManager->persist($note);
            }

            $entityManager->flush();
        } else {
            return $this->redirectToRoute('app_login');
        }


        return $this->redirectToRoute('app_product_show', [
            'slug' => $product->getSlug()
        ]);
    }



}
