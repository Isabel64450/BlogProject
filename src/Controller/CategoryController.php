<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository): Response

    {
       $categories=$categoryRepository->findAll();
    
    
    return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/new',name:'app_category_new', methods:['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form =$this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/new.html.twig',[
            'form'=> $form->createView(),
        ]);
    }

    #[Route('/category/edit/{id}', name:'app_category_edit',methods:['GET','POST'] )]
    public function edit(Category $category, Request $request, EntityManagerInterface $em) : Response
     {    $form = $this->createForm(CategoryType::class, $category);
          $form -> handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('app_category');
          }
        return $this->render('category/edit.html.twig',[
            'form' => $form->createView(),
            'category'=>$category,
        ]);
    }
}
