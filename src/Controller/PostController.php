<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PostType;


/**
 * @Route("/post", name="post")
 * @param PostRepository $postRepository
 * @return
 */

class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(PostRepository $postRepository) {

        $posts =  $postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     */
    public function create(Request $request) {
        //create a new post
        //$post->setTitle('This is going to be a tilte');
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        //entity manager
        $em = $this->getDoctrine()->getManager();
       // $em->persist($post);
        //$em->flush();

        //return a response
        return $this->render('post/create.html.twig',[
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/show/{id}", name="show")
     *
     */
    public function show(Post $post) {

        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);

    }

    /**
     * @Route("/delete/{id}", name="delete")
     *
     */

    public function remove(Post $post) {

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'Post was removed' );
        return $this->redirect($this->generateUrl('postindex'));


    }
}
