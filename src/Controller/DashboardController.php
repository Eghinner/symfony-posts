<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\Comentarios;
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
    	$coment = $entityManager->getRepository(Comentarios::class)->findAll();

    	$pagination = $paginator->paginate(
        	$query,
        	$request->query->getInt('page', 1), 10);

        return $this->render('dashboard/index.html.twig', [
            'coments' => $coment,
            'pagination' => $pagination
        ]);
    }
}
