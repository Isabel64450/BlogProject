<?php

namespace App\Controller;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomepageController extends AbstractController
{
   #[Route('/homepage', name: 'app_homepage')]
    public function index(PostRepository $postRepository, CategoryRepository $categoryRepository): Response
    {
        $posts = $postRepository->findBy(
            ['published' => true],
            ['id' => 'DESC'], 
            6 
        );
        $categories = $categoryRepository->findAll();
        return $this->render('homepage/index.html.twig', [
            'posts' => $posts,
            'categories' => $categories
        ]);
    }

     #[Route('/homepage/category/{id}', name: 'homepage_category')]
     public function postsByCategory(Category $category, PostRepository $postRepository, CategoryRepository $categoryRepository): Response
     {
    
    $posts = $postRepository->findBy(
        ['published' => true, 'category' => $category],
        ['createdAt' => 'DESC'],
        5
    );    
    $categories = $categoryRepository->findAll();

    return $this->render('homepage/index.html.twig', [
        'posts' => $posts,
        'categories' => $categories,
        'selectedCategory' => $category,
    ]);
    }


#[Route('/search', name: 'homepage_search', methods: ['GET'])]
public function search(Request $request, PostRepository $postRepository, CategoryRepository $categoryRepository): Response
{
    $query = $request->query->get('q'); 
    $categories = $categoryRepository->findAll();

    if ($query) {
        
        $posts = $postRepository->createQueryBuilder('p')
            ->where('p.published = true')
            ->andWhere('p.title LIKE :q OR p.content LIKE :q')
            ->setParameter('q', '%' . $query . '%')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    } else {
        
        $posts = $postRepository->findBy(
            ['published' => true],
            ['createdAt' => 'DESC'],
            5
        );
    }

    return $this->render('homepage/index.html.twig', [
        'posts' => $posts,
        'categories' => $categories,
        'searchQuery' => $query,
    ]);
}


}
