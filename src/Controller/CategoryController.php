<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{
    /**
     * @Route("/", name="category")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $categories = $entityManager->getRepository(Category::Class)->findAll();

        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/category/create", name="create_category")
     * @param Request $request
     */
    public function create(Request $request) {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category');
        }

        return $this->render('category/form.html.twig', [
           'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/category/update/{category}", name="update_category")
     * @param Request $request
     * @param Category $category
     * @return RedirectResponse|Response
     */
    public function update(Request $request, Category $category) {
        $form = $this->createForm(CategoryType::class, $category, [
           'action' => $this->generateUrl('update_category', [
               'category' => $category->getId()
           ]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('category');
        }

        return $this->render('category/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/category/delete/{category}", name="delete_category")
     * @param Category $category
     * @return RedirectResponse
     */
    public function delete(Category $category) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('category');
    }

    /**
     * @Route("/category/single/{category_id}", name="single_category")
     * @param Category $category_id
     */
    public function single(Category $category_id) {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->find($category_id);

        $articles = $category->getArticles();

        return $this->render('category/single.html.twig', [
            'articles' => $articles,
            'category' => $category
        ]);
    }
}
