<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FavoriteController extends AbstractController
{
    #[Route('/favorite', name: 'app_favorite')]
    public function index(UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        // force user to login page if not connected
        if(!$user)
        {
            return $this->redirectToRoute('app_login');
        }

        $email = $user->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $email]);

        $favorites = $user->getIdVideoGame();
        $favorites = $favorites->toArray();
        // dd($favorites);
        
        return $this->render('favorite/index.html.twig', [
            'email' => $email,
            'games' => $favorites
        ]);
    }
}
