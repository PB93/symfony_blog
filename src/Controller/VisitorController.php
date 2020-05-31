<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Visitor;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VisitorController extends Controller
{
    /**
     * @Route("/check-visitor", name="check_visitor")
     * @param Request $request
     * @return JsonResponse
     */
    public function check(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager
            ->getRepository(Visitor::Class)
            ->FindOneBy([
                'uuid' => $request->get('uuid')
            ]);

        return new JsonResponse([
            'exists' => $user ? true : false
        ]);
    }

    /**
     * @Route("/save-visitor", name="save_visitor")
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request)
    {
        $visitor = new Visitor();
        $entityManager = $this->getDoctrine()->getManager();
        $visitor->setBrowser($request->get('browser'));
        $visitor->setUuid($request->get('uuid'));
        $entityManager->persist($visitor);
        $entityManager->flush();
        return new JsonResponse([
            'success' => true
        ]);
    }


    /**
     * @Route("/show-visitors", name="show_visitors")
     *
     */
    public function showVisitors()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $visitors = $entityManager->getRepository(Visitor::Class)->FindAll();
        $statistics = [];

        foreach ($visitors as $visitor){
            $statistics[$visitor->getBrowser()][] = $visitor->getUuid();
        }

        //return $statistics;

       return $this->render('visitor/index.html.twig', [
            'controller_name' => 'VisitorController',
            'statistics' => $statistics
        ]);
    }
}
