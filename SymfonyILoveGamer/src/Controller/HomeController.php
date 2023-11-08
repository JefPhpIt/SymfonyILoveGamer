<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $user = $this->getUser();
        // check if user is logged
        if($user)
        {
            $user = $user->getUserIdentifier();
        }
        return $this->render('home/index.html.twig', [
            'email' => $user,
        ]);
    }

}
