<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(PostRepository $postRepository): Response
    {
        $posts=$postRepository->findAll();
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }



    #[Route('/post/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, PostRepository $postRepo): Response
    {
    $user = $this->getUser();
    $post = new Post();
    $form = $this->createForm(PostType::class, $post);
    $form->handleRequest($request);
        

    if ($form->isSubmitted() && $form->isValid()) {
        $post->setCreatedAt(new \DateTimeImmutable());
        $post->setAuthor($user);
        $post->setPublished(false);   
        $post->setRejected(false);   

        $em->persist($post);
        $em->flush();

        /* $this->addFlash('success', 'Your post has been submitted for review.'); */
        return $this->redirectToRoute('app_post');
    }

    return $this->render('post/new.html.twig', [
        'form' => $form->createView(),
    ]);
}


#[Route('/post/edit/{id}', name: 'app_post_edit', methods: ['GET', 'POST'])]
public function edit(Post $post, Request $request, EntityManagerInterface $em): Response
{
    $form = $this->createForm(PostType::class, $post);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $em->flush();

        return $this->redirectToRoute('app_post');
    }

    return $this->render('post/edit.html.twig', [
        'form' => $form->createView(),
        'post' => $post,
    ]);
}


#[Route('/post/{id}', name: 'app_post_show')]
public function show(Post $post): Response
{
    return $this->render('post/show.html.twig', [
        'post' => $post,
    ]);
}



#[Route('/my-posts', name: 'app_my_posts')]
public function myPosts(PostRepository $postRepository, Security $security): Response
{
    $user = $security->getUser();

    $posts = $postRepository->findBy([
        'author' => $user
    ], ['id' => 'DESC']);

    return $this->render('post/my_posts.html.twig', [
        'posts' => $posts,
    ]);
}

#[Route('/admin/posts', name: 'admin_posts')]
#[IsGranted('ROLE_ADMIN')]
public function review(PostRepository $postRepository): Response
{
    $posts = $postRepository->findBy([
        'published' => false,
        'rejected' => false
    ], ['createdAt' => 'DESC']);

    return $this->render('post/admin/posts.html.twig', [
        'posts' => $posts,
    ]);
}

   #[Route('/admin/post/{id}/approve', name: 'admin_post_approve')]
   #[IsGranted('ROLE_ADMIN')]
   public function approve(Post $post, EntityManagerInterface $em): Response
{
    $post->setPublished(true);

    $em->flush();

    return $this->redirectToRoute('admin_posts');
}



#[Route('/admin/post/{id}/reject', name: 'admin_post_reject')]
#[IsGranted('ROLE_ADMIN')]
public function rejected(Post $post, EntityManagerInterface $em): Response
{
    
    $post->setPublished(false);  
    $post->setRejected(true);   

    $em->flush();

   /*  $this->addFlash('success', 'Post has been rejected.'); */

    return $this->redirectToRoute('admin_posts'); 
}










}
