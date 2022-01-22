<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\Comentarios;
use App\Form\PostsType;
use App\Form\ComentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
    /**
     * @Route("/register-post", name="posts")
     */
    public function index(Request $request, SluggerInterface $slugger): Response
    {

    	$post = new Posts();
    	$form = $this->createForm(PostsType::class, $post);
    	$form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid()) {

    		/** @var UploadedFile $foto */
            $foto = $form->get('foto')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($foto) {
                $originalFilename = pathinfo($foto->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$foto->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $foto->move(
                        $this->getParameter('foto_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                	throw new Exception("Error Processing Request");

                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $post->setFoto($newFilename);
            }

    		$user = $this->getUser();
    		$post->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard');
    	}

        return $this->render('posts/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/post/{id}", name="showPost")
     */

    public function verPost(int $id, Request $request): Response
    {
       $entityManager = $this->getDoctrine()->getManager();
       $post = $entityManager->getRepository(Posts::class)->find($id);

       $coment = new Comentarios();
       $form = $this->createForm(ComentType::class, $coment);
       $form->handleRequest($request);

       // $coment_post = $entityManager->getRepository(Comentarios::class)->findBy(['posts' => $id]);
       $coment_post = $entityManager->getRepository(Comentarios::class)->findAllComents($id);

       if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            $coment->setUser($user);
            $coment->setPosts($post);
            $em = $this->getDoctrine()->getManager();
            $em->persist($coment);
            $em->flush();

            return $this->redirectToRoute('showPost', array('id' => $id));
       }

       return $this->render('posts/post.html.twig', [
        'post' => $post,
        'coment_post' => $coment_post,
        'form' => $form->createView()
       ]);
    }

    /**
     * @Route("/my-posts", name="myPost")
     */

    public function myPosts(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $posts = $entityManager->getRepository(Posts::class)->findBy(['user' => $user]);

        return $this->render('posts/my-posts.html.twig', [
        'posts' => $posts
       ]);
    }


}
