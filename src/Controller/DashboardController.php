<?php

namespace App\Controller;

use App\Entity\Posts;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
    	$entityManager = $this->getDoctrine()->getManager();
    	$query = $entityManager->getRepository(Posts::class)->findAllPosts();
    	$posts2 = $entityManager->getRepository(Posts::class)->findAll();

    	$pagination = $paginator->paginate(
        	$query, /* query NOT result */
        	$request->query->getInt('page', 1), /*page number*/
        	2 /*limit per page*/
    	);

        return $this->render('dashboard/index.html.twig', [
            // 'posts' => $posts,
            'pagination' => $pagination
        ]);
    }
}
