<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RawgController extends AbstractController
{
    #[Route('/games', name: 'rawg_games')]
    public function getGames(HttpClientInterface $httpClient): Response
    {
        $url = $this->getParameter('rawg_api_url');
        $apiKey = $this->getParameter('rawg_api_key');

        $response = $httpClient->request('GET', $url, [
            'query' => [
                'key' => $apiKey
                // ordering?
            ]
        ]);

        $data = $response->toArray();

        // dd($data);

        return $this->render('rawg/index.html.twig', [
            'games' => $data['results'], // Adjust the array key to match your API response structure
        ]);
    }
}
