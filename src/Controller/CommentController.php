<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends Controller
{
    /**
     * @Route("/comment/create/{article}", name="comment_create_form")
     * @param Request $request
     * @param Article $article
     */
   public function create(Request $request, Article $article) {
       $comment = new Comment();

       $form = $this->createForm(CommentType::class, $comment, [
        'action' => $this->generateUrl('comment_create_form', [
            'article' => $article->getId()
        ]),
           'method' => 'POST',
       ]);

       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           $comment = $form->getData();
           $comment->setCreatedAt(new \DateTime('now'));
           $comment->setArticle($article);

           $entityManager = $this->getDoctrine()->getManager();

           $entityManager->persist($comment);
           $entityManager->flush();

           return new JsonResponse([
               'author' => $comment->getAuthor(),
               'createdAt' => date("F jS \\a\\t H:i:s", $comment->getCreatedAt()->getTimestamp()),
               'content'=> $comment->getContent()
           ]);
       }

       return $this->render('comment/form.html.twig', [
          'form' => $form->createView(),
          'article' => $article
       ]);
   }
}
