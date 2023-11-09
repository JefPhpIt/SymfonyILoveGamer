<?php

namespace App\Controller;

use App\Entity\VideoGame;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/api/search', name: 'api_search', methods: ['POST'])]
    public function index(Request $request): Response
    {
        dd($request);
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }
}
