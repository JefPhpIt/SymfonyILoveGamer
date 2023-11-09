<?php

namespace App\Controller;

use App\Form\SearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request, HttpClientInterface $client ): Response
    {
        $form = $this->createForm(SearchFormType::class) ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['gameTitle'];
            $response = $client->request(
                'GET',
                "https://api.rawg.io/api/games?key=" . "320936eb892d49cea0ef502ce752b61a" . "&search=" . $search
            );
            //dd($response->toArray());
            return $this->render('search/index.html.twig', [
                'games' => $response->toArray(),
            ]);
        }


        return $this->render('home/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
