<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RawgController extends AbstractController
{
    #[Route('/games', name: 'rawg_games', methods: ['GET'])]
    public function getGames(Request $request, HttpClientInterface $httpClient): Response
    {
        


        $url = $this->getParameter('rawg_api_url');
        $apiKey = $this->getParameter('rawg_api_key');

        $nextPageUrl = $request->query->get('nextPageUrl', $url); // Use the next page URL if available

        $response = $httpClient->request('GET', $nextPageUrl, [
            'query' => [
                'key' => $apiKey,
                // ordering?
            ]
        ]);
        

        $data = $response->toArray();

        $games = $data['results']; // get the list of games

        $previousPageURL = $data['previous']; // get the previous page URL
        $nextPageUrl = $data['next']; // get the next page URL

        // dd($data);

        return $this->render('rawg/index.html.twig', [
            'games' => $games,
            'nextPageUrl' => $nextPageUrl,
            'previousPageUrl' => $previousPageURL
        ]);
    }
}
