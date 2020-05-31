<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

class ArticleController extends Controller
{
    /**
     * @Route("/article", name="article")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $articles = $entityManager->getRepository(Article::Class)->FindAll();

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/article/single/{article}" , name="single_article")
     * @param Article $article
     */
    public function single(Article $article){
        $category_id = $article->getCategory()->getId();
        return $this->render('article/single.html.twig', [
            'article' => $article,
            'category_id' => $category_id
            ]);
    }

    /**
     * @Route("/article/create/{category}", name="create_article")
     * @param Request $request
     * @param Category $category
     * @return RedirectResponse|Response
     */
    public function create(Request $request, Category $category){
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $article->setCategory($category);

            $articleFile = $form->get('file')->getData();
            if ($articleFile) {
                $originalFilename = pathinfo($articleFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$articleFile->guessExtension();

                try {
                    $articleFile->move(
                        $this->getParameter('user_files_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                $article->setFile($newFilename);

            }

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($article);
            $entityManager->flush();

            $category_id = $article->getCategory()->getId();

            return $this->redirectToRoute('single_category', ['category_id' => $category_id]);
        }

        return $this->render('article/form.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/article/update/{article}", name="update_article")
     * @param Request $request
     * @param Article $article
     */
    public function update(Request $request, Article $article){
        $form = $this->createForm(ArticleType::class, $article, [
           'action' => $this->generateUrl('update_article', [
               'article' => $article->getId()
           ]),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $articleFile = $form->get('file')->getData();
            if ($articleFile) {
                $originalFilename = pathinfo($articleFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$articleFile->guessExtension();

                try {
                    $articleFile->move(
                        $this->getParameter('user_files_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                $article->setFile($newFilename);

            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('single_article', ['article' => $article->getId()]);
        }

        $category = $article->getCategory();
        return $this->render('article/form.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/article/delete/{article}", name="delete_article")
     * @param Article $article
     */
    public function delete(Article $article){
        $category_id = $article->getCategory()->getId();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();
        return $this->redirectToRoute('single_category', ['category_id' => $category_id]);
    }

}
