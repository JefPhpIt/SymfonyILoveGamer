<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\UserRepository;
use App\Repository\VideoGameRepository;
use App\Service\VideoGameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home_index')]
    public function index(UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        return $this->render('home/index.html.twig', [
            'user' => $user,
            'platforms' => $userRepository->findPlatformsByUser($user)
        ]);
    }

}
