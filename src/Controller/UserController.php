<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {   
        $users=$userRepository->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }
   #[Route('/user/{id}/delete', name: 'app_user_delete', methods: ['POST','GET'], requirements: ['id' => '\d+'])]
    public function delete( User $user,EntityManagerInterface $em): Response 
    {

    $em->remove($user);
    $em->flush();
    return $this->redirectToRoute('app_user');
}
}
