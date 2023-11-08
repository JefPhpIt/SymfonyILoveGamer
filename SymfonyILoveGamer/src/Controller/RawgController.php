<?php

namespace App\Controller;

use App\Entity\VideoGame;
use App\Repository\UserRepository;
use App\Repository\VideoGameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class RawgController extends AbstractController
{
    #[Route('/games', name: 'rawg_games', methods: ['GET'])]
    public function getGames(Request $request, HttpClientInterface $httpClient, EntityManagerInterface $entityManager, UserRepository $userRepository, VideoGameRepository $videoGameRepository): Response
    {
        // check if user added a game in favorite
        $id = $request->query->get('id');

        if($id) 
        {
            $this->addFavorite($id, $httpClient, $entityManager, $userRepository, $videoGameRepository);
        }

        $url = $this->getParameter('rawg_api_url');
        $apiKey = $this->getParameter('rawg_api_key');

        $newPageUrl = $request->query->get('newPageUrl', $url); // Use the next page URL if available

        $response = $httpClient->request('GET', $newPageUrl, [
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

    public function addFavorite(int $id, HttpClientInterface $httpClient, EntityManagerInterface $entityManager, UserRepository $userRepository, VideoGameRepository $videoGameRepository): bool
    {
        // if not connected leave early
        if(!$this->getUser()) 
        {
            return false;
        }

        $url = $this->getParameter('rawg_api_url').'/'.$id;
        $apiKey = $this->getParameter('rawg_api_key');

        // get the game data from API
        $response = $httpClient->request('GET', $url, [
            'query' => [
                'key' => $apiKey,
            ]
        ]);

        $data = $response->toArray();

        // dd($data);

        // check if game isnt already in database
        $game = $videoGameRepository->findOneBy(['id_API' => $id]);
        // dd($game);
        if(is_null($game))
        {
            // register the game in our database for favorite access in local for user
            $game = new VideoGame();
            
            $game
                ->setIdAPI($id)
                ->setName($data['name'])
                ->setBackgroundImage($data['background_image']);

            $entityManager->persist($game);
            $entityManager->flush();
        }
        
        // add relation between user and videogame in our database
        $email = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $email]);
        $user->addIdVideogame($game);

        $entityManager->flush();

        return true;
    }
}
