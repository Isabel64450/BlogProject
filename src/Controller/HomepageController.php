<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomepageController extends AbstractController
{
   #[Route('/homepage', name: 'app_homepage')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy(
            ['published' => true],
            ['id' => 'DESC'], 
            6 
        );

        return $this->render('homepage/index.html.twig', [
            'posts' => $posts,
        ]);
    }
}
