<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\CategoryComment;
use App\Form\CategoryCommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryCommentController extends Controller
{
    /**
     * @Route("/categorycomment/create/{category}", name="category_comment_create_form")
     * @param Request $request
     * @param Category $category
     */
    public function create(Request $request, Category $category) {

        $comment = new CategoryComment();

        $form = $this->createForm(CategoryCommentType::class, $comment, [
           'action' => $this->generateUrl('category_comment_create_form', [
               'category' => $category->getId()
           ]) ,
            'method' => 'POST'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setCreatedAt(new \DateTime('now'));
            $comment->setCategory($category);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            return new JsonResponse([
                'author' => $comment->getAuthor(),
                'createdAt' => date("F jS \\a\\t H:i:s", $comment->getCreatedAt()->getTimestamp()),
                'content'=> $comment->getContent()
            ]);
        }

        return $this->render('category_comment/form.html.twig', [
           'form' => $form->createView(),
           'category' => $category
        ]);
    }
}
